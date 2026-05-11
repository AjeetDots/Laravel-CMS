<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\ContactPageContent;
use App\Models\GalleryItem;
use App\Models\PhoneCountry;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionServiceInterface $contactSubmissionService
    ) {
    }

    public function index()
    {
        $phoneCountries = PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']);
        $contactPage = ContactPageContent::viewDataWithDefaults();
        $contactHeroUrl = $this->resolveContactHeroUrl($contactPage['hero_bg_image'] ?? null);

        return view('frontend.contact', compact('phoneCountries', 'contactPage', 'contactHeroUrl'));
    }

    private function resolveContactHeroUrl(?string $storedPath): ?string
    {
        $storedPath = $storedPath !== null ? trim($storedPath) : '';
        if ($storedPath !== '') {
            return filter_var($storedPath, FILTER_VALIDATE_URL)
                ? $storedPath
                : asset('storage/'.ltrim($storedPath, '/'));
        }

        $contactHeroImage = GalleryItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->value('image');

        if (! $contactHeroImage) {
            return null;
        }

        return filter_var($contactHeroImage, FILTER_VALIDATE_URL)
            ? $contactHeroImage
            : asset('storage/'.$contactHeroImage);
    }

    public function store(StoreContactRequest $request)
    {
        $payload = $request->validated();
        unset($payload['_form_context']);
        $this->contactSubmissionService->submit($payload);

        $url = match ($request->input('_form_context', 'contact')) {
            'home' => route('home').'#home-contact',
            default => route('contact').'#contactFormPanel',
        };

        return redirect($url)
            ->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
