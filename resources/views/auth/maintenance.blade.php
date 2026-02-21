@extends('layouts.auth', ['title' => 'Maintenance'])

@section('content')

<div class="card">
    <div class="card-body p-5">
        <div class="text-center">
            <img src="/images/extra/card/maintenance.png" alt="" class="img-fluid mb-3">
            <h6 class="mb-2 fw-medium text-dark fs-24">We are down on maintenance right now</h6>
            <p class="text-muted mb-0 text-wrap fs-15">This website is currently undergoing Scheduled maintenance.</p>
            <div class="mt-3 text-center">
                <a class="btn btn-primary w-50" href="{{ route('any', 'index')}}">Back to Dashboard <i class="fas fa-redo ms-1"></i></a>
            </div>
        </div>
    </div><!--end card-body-->
</div><!--end card-->

@endsection