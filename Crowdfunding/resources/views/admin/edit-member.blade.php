@extends('admin.layout')

@section('content')
<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <a class="text-reset" href="{{ url('panel/admin/members') }}">{{ __('admin.members') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">{{ __('admin.edit') }}</span>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">{{ $data->name }}</span>
</h5>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            @include('errors.errors-forms')

            <div class="card shadow-custom border-0">
                <div class="card-body p-lg-5">
                    <form class="form-horizontal" method="POST" action="{{ url('panel/admin/members/'.$data->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label text-lg-end">{{ trans('misc.avatar') }}</label>
                            <div class="col-sm-10">
                                <img src="{{ asset('public/avatar/'.$data->avatar) }}" width="80" height="80" class="rounded-circle" alt="Avatar of {{ $data->name }}" />
                            </div>
                        </div>

                       <!-- Passport Front -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label text-lg-end">Passport (Front)</label>
    <div class="col-sm-10">
        @if($data->passport_front)
            <img src="{{ asset('public/'.$data->passport_front) }}" alt="Passport Front" class="w-24 h-24 object-cover rounded-md mb-4">
        @else
            <p>No passport front image uploaded</p>
        @endif
    </div>
</div>

<!-- Passport Back -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label text-lg-end">Passport (Back)</label>
    <div class="col-sm-10">
        @if($data->passport_back)
            <img src="{{ asset('public/'.$data->passport_back) }}" alt="Passport Back" class="w-24 h-24 object-cover rounded-md mb-4">
        @else
            <p>No passport back image uploaded</p>
        @endif
    </div>
</div>

<!-- National ID Front -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label text-lg-end">National ID (Front)</label>
    <div class="col-sm-10">
        @if($data->national_id_front)
            <img src="{{ asset('public/'.$data->national_id_front) }}" alt="National ID Front" class="w-24 h-24 object-cover rounded-md mb-4">
        @else
            <p>No national ID front image uploaded</p>
        @endif
    </div>
</div>

<!-- National ID Back -->
<div class="row mb-3">
    <label class="col-sm-2 col-form-label text-lg-end">National ID (Back)</label>
    <div class="col-sm-10">
        @if($data->national_id_back)
            <img src="{{ asset('public/'.$data->national_id_back) }}" alt="National ID Back" class="w-24 h-24 object-cover rounded-md mb-4">
        @else
            <p>No national ID back image uploaded</p>
        @endif
    </div>
</div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label text-lg-end">{{ trans('auth.password') }}</label>
                            <div class="col-sm-10">
                                <input name="password" type="password" class="form-control" placeholder="{{ trans('admin.password_no_change') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-dark mt-3 px-5 me-2">{{ __('admin.save') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
