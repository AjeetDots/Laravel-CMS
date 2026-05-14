@if(isset($items) && $items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->hasPages())
    <div class="d-flex justify-content-center mt-3">{{ $items->withQueryString()->links() }}</div>
@endif
