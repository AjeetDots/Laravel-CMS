<?php
namespace App\Http\Controllers\Frontend;

use App\Contracts\Frontend\HomePageServiceInterface;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct(
        private readonly HomePageServiceInterface $homePageService
    ) {
    }

    public function index()
    {
        return view('frontend.home', $this->homePageService->getPageData());
    }
}
