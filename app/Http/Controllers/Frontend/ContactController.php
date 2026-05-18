<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\ContactPageContent;
use App\Models\PhoneCountry;
use App\Support\ContactPageUrl;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionServiceInterface $contactSubmissionService
    ) {
    }

    public function index()
    {
        $page = ContactPageUrl::activePage();

        if ($page === null) {
            abort(404);
        }

        $phoneCountries = PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']);
        $contactPage = ContactPageContent::viewDataWithDefaults();
        $contactHeroUrl = ContactPageContent::resolveHeroBackgroundUrl($contactPage['hero_bg_image'] ?? null);

        return view('frontend.contact', compact('phoneCountries', 'contactPage', 'contactHeroUrl', 'page') + [
            'seoModel' => $page,
        ]);
    }
    public function store(StoreContactRequest $request)
    {
        $payload = $request->validated();
        unset($payload['_form_context']);
        $this->contactSubmissionService->submit($payload);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Thank you! Your message has been sent successfully.',
            ]);
        }

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
