@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('content')

<div class="page-header-bar d-flex flex-wrap justify-content-between align-items-center gap-3">
    <h1 class="mb-0">Newsletter Subscribers <span class="badge badge-active ms-2" style="font-size:.8rem;">{{ $subscribers->count() }}</span></h1>
    <a href="{{ route('admin.newsletter.export') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-file-export me-1"></i> Export CSV
    </a>
</div>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($subscribers->isEmpty())
            <div class="text-center py-5 text-muted">No subscribers match your filters.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" data-admin-dt data-dt-renumber-col="0">
                    <thead>
                        <tr>
                            <th width="50" data-dt-orderable="false">Sr.</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed</th>
                            <th width="80" data-dt-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscribers as $sub)
                        <tr>
                            <td class="text-muted" style="font-size:.82rem;">{{ $loop->iteration }}</td>
                            <td style="font-weight:600;">{{ $sub->email }}</td>
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
                                      data-delete-confirm="This subscriber will be removed from the mailing list. They may subscribe again later.">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-icon btn-outline-danger"
                                            data-bs-toggle="tooltip" title="Remove Subscriber"><i class="fas fa-trash-alt"></i></button>
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
