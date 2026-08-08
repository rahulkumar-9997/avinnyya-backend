@extends('backend.layouts.master')
@section('title','Blog')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Blog List</h4>
                        <div class="flex-shrink-0">
                            <div class="form-check form-switch form-switch-right form-switch-md">
                                <a href="{{ route('manage-blog.create') }}" class="btn btn-warning custom-toggle active">Add New Blog</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">                        
                        <div class="live-preview">
                            <div class="table-responsive table-card">
                                @include('backend.pages.blog.partials.blog-list', ['blogs' => $blogs])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

@endpush