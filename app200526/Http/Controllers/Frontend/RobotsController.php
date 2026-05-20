<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $custom = Setting::get('robots_txt', '');

        $content = $custom ?: implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /admin/login',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ]);

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
