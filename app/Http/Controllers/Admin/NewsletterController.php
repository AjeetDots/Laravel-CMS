<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Concerns\AppliesAdminTableFilters;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller {
    use AppliesAdminTableFilters;

    public function index() {
        $query = NewsletterSubscriber::query()->orderByDesc('created_at');
        $this->applyAdminStatus($query, request('status'));
        $this->applyAdminSearch($query, request('q'), ['email']);
        $subscribers = $query->latest()->get();

        return view('admin.newsletter.index', compact('subscribers'));
    }

    /**
     * Download all newsletter subscribers as CSV (Excel-friendly UTF-8).
     */
    public function export(): StreamedResponse {
        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['Sr.', 'Email', 'Status', 'Subscribed at']);

            $serial = 0;
            foreach (NewsletterSubscriber::query()->orderByDesc('created_at')->cursor() as $sub) {
                $serial++;
                fputcsv($handle, [
                    $serial,
                    $sub->email,
                    $sub->is_active ? 'Active' : 'Inactive',
                    $sub->created_at?->format('d M Y H:i') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    public function destroy(NewsletterSubscriber $subscriber) {
        $subscriber->delete();
        return back()->with('success', 'The subscriber has been removed.');
    }
}
