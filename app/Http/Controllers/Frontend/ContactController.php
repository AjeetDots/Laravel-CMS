<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\Contact;

class ContactController extends Controller {
    public function index() {
        return view('frontend.contact');
    }
    public function store(StoreContactRequest $request) {
        Contact::create($request->validated());
        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
