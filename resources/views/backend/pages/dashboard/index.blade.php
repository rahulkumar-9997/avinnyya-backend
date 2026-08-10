@extends('backend.layouts.master')
@section('title','Dashboard')
@push('styles')
@endpush
@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="h-100">                        
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Blog</p>
                                            </div>                                            
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-2">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">
                                                    {{ $blog }}
                                                </h4>                                                
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endsection