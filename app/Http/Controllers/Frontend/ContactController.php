<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContactSubmissionServiceInterface $contactSubmissionService
    ) {
    }

    public function index()
    {
        return view('frontend.contact');
    }

    public function store(StoreContactRequest $request)
    {
        $this->contactSubmissionService->submit($request->validated());

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
