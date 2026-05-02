<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $pages = Page::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get()
            ->map(fn($p) => [
                'url'     => url('/' . $p->slug),
                'lastmod' => $p->updated_at->toAtomString(),
                'freq'    => 'monthly',
                'priority'=> '0.7',
            ]);

        $posts = BlogPost::where('is_active', true)
            ->select('slug', 'updated_at', 'published_at')
            ->get()
            ->map(fn($p) => [
                'url'     => route('blog.show', $p->slug),
                'lastmod' => $p->updated_at->toAtomString(),
                'freq'    => 'weekly',
                'priority'=> '0.8',
            ]);

        $services = Service::where('is_active', true)
            ->select('slug', 'updated_at')
            ->get()
            ->map(fn($s) => [
                'url'     => route('services.show', $s->slug),
                'lastmod' => $s->updated_at->toAtomString(),
                'freq'    => 'monthly',
                'priority'=> '0.6',
            ]);

        $urls = collect([
            [
                'url'     => url('/'),
                'lastmod' => now()->toAtomString(),
                'freq'    => 'daily',
                'priority'=> '1.0',
            ],
        ])->concat($posts)->concat($pages)->concat($services);

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
