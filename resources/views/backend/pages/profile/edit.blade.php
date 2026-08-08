@extends('backend.layouts.master')
@section('title','Profile')
@push('styles')
@endpush
@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card">

                    <div class="card-body p-4">
                        <form action="{{ route('profile.update', auth()->user()->id) }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="firstnameInput" class="form-label">Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="firstnameInput"
                                            placeholder="Enter your name"
                                            name="name"
                                            value="{{ old('name', auth()->user()->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="lastnameInput" class="form-label">Email</label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="lastnameInput"
                                            placeholder="Enter your email"
                                            name="email"
                                            value="{{ old('email', auth()->user()->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="phonenumberInput" class="form-label">Phone Number</label>
                                        <input
                                            type="text"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            id="phonenumberInput"
                                            placeholder="Enter your phone number"
                                            name="phone_number"
                                            value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="profileImageInput" class="form-label">Profile Image</label>
                                        <input
                                            type="file"
                                            class="form-control @error('profile_image') is-invalid @enderror"
                                            id="profileImageInput"
                                            accept="image/png, image/jpeg, image/webp"
                                            name="profile_image">
                                        @error('profile_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if (auth()->user()->profile_img)
                                            <div class="mt-2">
                                                <img
                                                    src="{{ asset('storage/images/profile/' . auth()->user()->profile_img) }}"
                                                    alt="Current profile image"
                                                    width="60"
                                                    height="60"
                                                    style="object-fit: cover; border-radius: 50%;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Update Password</label>
                                        <input
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            placeholder="Leave blank to keep current password"
                                            name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password_confirmation"
                                            placeholder="Re-enter new password"
                                            name="password_confirmation">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3 pb-2">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea
                                            class="form-control @error('bio') is-invalid @enderror"
                                            id="bio"
                                            placeholder="Enter your bio"
                                            rows="3"
                                            name="bio">{{ old('bio', auth()->user()->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary"  id="updateProfileBtn">Update</button>
                                        <button type="button" class="btn btn-soft-success">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
    $(function () {
        $('#profileUpdateForm').on('submit', function () {
            var $btn = $('#updateProfileBtn');
            if ($btn.prop('disabled')) {
                return false;
            } 
            $btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...');
            return true;
        });
    });
</script>
@endpush