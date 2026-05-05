<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller {
    public function index() {
        $subscribers = NewsletterSubscriber::orderByDesc('created_at')->paginate(20);
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
            fputcsv($handle, ['ID', 'Email', 'Name', 'Status', 'Subscribed at']);

            foreach (NewsletterSubscriber::query()->orderByDesc('created_at')->cursor() as $sub) {
                fputcsv($handle, [
                    $sub->id,
                    $sub->email,
                    $sub->name ?? '',
                    $sub->is_active ? 'Active' : 'Inactive',
                    $sub->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
    public function destroy(NewsletterSubscriber $subscriber) {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }
}
