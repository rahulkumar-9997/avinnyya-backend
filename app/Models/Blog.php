<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'slug',
        'short_desc',
        'content',
        'reading_time',
        'meta_title',
        'meta_description',
        'main_image',
        'page_image',
        'status',
        'visitor_count',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Blog has many paragraphs.
     */
    public function paragraphs(): HasMany
    {
        return $this->hasMany(BlogParagraph::class);
    }

    /**
     * Blog has many images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(BlogImage::class);
    }   
}