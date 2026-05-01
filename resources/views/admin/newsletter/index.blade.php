@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('content')

<div class="page-header-bar d-flex justify-content-between align-items-center">
    <h1>Newsletter Subscribers <span class="badge bg-primary ms-2">{{ $subscribers->total() }}</span></h1>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Subscribed</th>
                    <th width="80">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $sub)
                <tr>
                    <td class="text-muted" style="font-size:.82rem;">{{ $sub->id }}</td>
                    <td style="font-weight:600;">{{ $sub->email }}</td>
                    <td>{{ $sub->name ?? '—' }}</td>
                    <td>
                        @if($sub->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;">{{ $sub->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST"
                              onsubmit="return confirm('Remove subscriber?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No subscribers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($subscribers->hasPages())
    <div class="mt-3">{{ $subscribers->links() }}</div>
@endif

@endsection
