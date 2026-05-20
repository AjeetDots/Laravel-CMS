<?php

namespace App\Contracts\Frontend;

interface HomePageServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getPageData(): array;
}
