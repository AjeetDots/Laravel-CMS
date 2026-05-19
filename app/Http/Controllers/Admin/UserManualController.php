<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Response;

class UserManualController extends Controller
{
    public function index()
    {
        return view('admin.user-manual.index');
    }

    /**
     * Download the user manual as a .doc file (Word-compatible HTML).
     */
    public function export(): Response
    {
        $siteName = trim((string) (Setting::get('site_name') ?? ''));
        $siteName = $siteName !== '' ? $siteName : (string) config('app.name');

        $html = view('admin.user-manual.export-word', [
            'wordExport' => true,
            'siteName' => $siteName,
            'exportedAt' => now(),
        ])->render();

        // UTF-8 BOM helps Microsoft Word display special characters correctly.
        $html = "\xEF\xBB\xBF" . $html;

        $filename = 'website-user-manual-' . now()->format('Y-m-d') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, no-cache',
        ]);
    }
}
