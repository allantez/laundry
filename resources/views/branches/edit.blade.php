@extends('layouts.vertical')

@section('content')
<div class="container">
    <h2>Edit Branch</h2>

    <form method="POST" action="{{ route('branches.update', $branch) }}">
        @csrf
        @method('PUT')
        @include('branches._form')
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
