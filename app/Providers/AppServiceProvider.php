<?php

namespace App\Providers;

use App\Contracts\Frontend\ContactSubmissionServiceInterface;
use App\Contracts\Frontend\HomePageServiceInterface;
use App\Contracts\Frontend\NewsletterSubscriptionServiceInterface;
use App\Services\Frontend\ContactSubmissionService;
use App\Services\Frontend\HomePageService;
use App\Services\Frontend\NewsletterSubscriptionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(HomePageServiceInterface::class, HomePageService::class);
        $this->app->bind(ContactSubmissionServiceInterface::class, ContactSubmissionService::class);
        $this->app->bind(NewsletterSubscriptionServiceInterface::class, NewsletterSubscriptionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
