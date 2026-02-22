@extends('layouts.vertical')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Branches</h2>

            @can('create branches')
                <a href="{{ route('branches.create') }}" class="btn btn-primary">
                    + New Branch
                </a>
            @endcan
        </div>

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" @selected(request('status') == 'active')>Active</option>
                        <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>City</th>
                    <th>Status</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->code }}</td>
                        <td>{{ $branch->city }}</td>
                        <td>
                            <span class="badge bg-{{ $branch->status['color'] }}">
                                {{ $branch->status['label'] }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('branches.show', $branch) }}" class="btn btn-sm btn-info">View</a>

                            @can('edit branches')
                                <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endcan

                            @can('delete branches')
                                <form method="POST" action="{{ route('branches.destroy', $branch) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete branch?')">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No branches found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $branches->withQueryString()->links() }}
    </div>
@endsection
