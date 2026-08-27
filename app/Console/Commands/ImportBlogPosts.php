<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ImageHelper;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportBlogPosts extends Command
{
    protected $signature = 'blog:import
                            {path : Folder containing the JSON post files}
                            {--status=draft : Status to save imported posts as}
                            {--dry-run : List what would be imported without writing anything}';

    protected $description = 'Bulk-import blog posts from a folder of JSON files';

    public function handle(): int
    {
        $path = rtrim($this->argument('path'), '/');
        if (!is_dir($path)) {
            $this->error("Folder not found: {$path}");
            return self::FAILURE;
        }
        $files = glob($path . '/*.json');
        if (empty($files)) {
            $this->error('No .json files found in that folder.');
            return self::FAILURE;
        }
        $dryRun = (bool) $this->option('dry-run');
        $imported = 0;
        $skipped = 0;
        $failed = 0;
        foreach ($files as $file) {
            $raw = json_decode(file_get_contents($file), true);
            $slug = $raw['slug'] ?? null;
            $title = $raw['basic_details']['title'] ?? null;
            $contentHtml = $raw['content_html'] ?? null;

            if (!$slug || !$title || !$contentHtml) {
                $this->warn('Skipping (missing slug/title/content): ' . basename($file));
                $skipped++;
                continue;
            }
            if (Blog::where('slug', $slug)->exists()) {
                $this->line("Already imported, skipping: {$slug}");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->info("[dry-run] Would import: {$slug}");
                $imported++;
                continue;
            }

            $mainImage = null;
            DB::beginTransaction();
            try {
                $bannerUrl = $raw['basic_details']['banner_image'] ?? null;
                if ($bannerUrl) {
                    $imageName = ImageHelper::generateFileName($title, 'blog-main');
                    $mainImage = ImageHelper::downloadSingleImageWebpOnly($bannerUrl, $imageName, 'blog');
                }

                $intro = strip_tags($raw['basic_details']['introduction'] ?? '');
                Blog::create([
                    'title'            => $title,
                    'slug'             => $slug,
                    'short_desc'       => Str::limit($intro, 497),
                    'content'          => $contentHtml,
                    'reading_time'     => $this->calculateReadingTime($contentHtml),
                    'meta_title'       => Str::limit($title, 255),
                    'meta_description' => Str::limit($intro, 157),
                    'main_image'       => $mainImage,
                    'page_image'       => null,
                    'status'           => 'published',
                    'visitor_count'    => 0,
                    'published_at'     => !empty($raw['basic_details']['date'])
                        ? Carbon::parse($raw['basic_details']['date'])
                        : null,
                ]);

                DB::commit();
                $this->info("Imported: {$slug}");
                $imported++;
            } catch (\Throwable $e) {
                DB::rollBack();
                if ($mainImage) {
                    ImageHelper::deleteSingleImage($mainImage, 'blog');
                }
                Log::error("Blog import failed for {$slug}: " . $e->getMessage());
                $this->error("Failed: {$slug} — {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        $this->table(['Imported', 'Skipped', 'Failed'], [[$imported, $skipped, $failed]]);

        return self::SUCCESS;
    }

    private function calculateReadingTime(?string $content): string
    {
        $content = strip_tags($content ?? '');
        $minutes = max(1, ceil(str_word_count($content) / 200));
        return $minutes . ' min read';
    }
}
