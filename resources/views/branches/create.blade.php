@extends('layouts.vertical')

@section('content')
<div class="container">
    <h2>Create Branch</h2>

    <form method="POST" action="{{ route('branches.store') }}">
        @csrf
        @include('branches._form')
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
