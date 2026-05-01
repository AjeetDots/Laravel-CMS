@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')

<div class="page-header-bar">
    <h1>Testimonials</h1>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Testimonial
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($testimonials->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-quote-right fa-2x mb-2"></i>
                <p>No testimonials yet. <a href="{{ route('admin.testimonials.create') }}">Add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Company</th>
                            <th>Message</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $t)
                            <tr>
                                <td>
                                    <div class="fw-500">{{ $t->client_name }}</div>
                                    <div class="text-muted" style="font-size:.82rem;">{{ $t->client_position }}</div>
                                </td>
                                <td>{{ $t->client_company ?? '—' }}</td>
                                <td>{{ Str::limit($t->message, 60) }}</td>
                                <td>
                                    @for($i=1;$i<=5;$i++)
                                        <i class="fas fa-star {{ $i <= $t->rating ? 'text-warning' : 'text-muted' }}" style="font-size:.75rem;"></i>
                                    @endfor
                                </td>
                                <td>
                                    <span class="badge {{ $t->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this testimonial?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
