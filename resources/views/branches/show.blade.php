@extends('layouts.vertical')

@section('content')
<div class="container">
    <h2>{{ $branch->name }}</h2>

    <p><strong>Code:</strong> {{ $branch->code }}</p>
    <p><strong>City:</strong> {{ $branch->city }}</p>
    <p><strong>Manager:</strong> {{ $branch->manager->name ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $branch->status['label'] }}</p>

    <hr>

    <h4>Monthly Statistics</h4>

    <ul>
        <li>Total Orders: {{ $statistics['total_orders'] }}</li>
        <li>Total Revenue: {{ number_format($statistics['total_revenue'],2) }}</li>
        <li>Pending Orders: {{ $statistics['pending_orders'] }}</li>
        <li>Completed Orders: {{ $statistics['completed_orders'] }}</li>
        <li>Total Customers: {{ $statistics['total_customers'] }}</li>
    </ul>

    <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning">
        Edit Branch
    </a>
</div>
@endsection
