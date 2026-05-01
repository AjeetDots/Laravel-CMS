<?php
namespace Database\Seeders;
use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder {
    public function run(): void {
        $eyebrow = 'Years of experience across web, mobile, design and cloud technologies.';
        $sliders = [
            ['title' => 'Professional Services', 'subtitle' => $eyebrow, 'image' => 'sliders/slide1.jpg', 'button_text' => 'Our Services', 'button_link' => '/services', 'sort_order' => 1],
            ['title' => 'Quality Solutions', 'subtitle' => $eyebrow, 'image' => 'sliders/slide2.jpg', 'button_text' => 'Get Started', 'button_link' => '/contact', 'sort_order' => 2],
            ['title' => 'Expert Team', 'subtitle' => $eyebrow, 'image' => 'sliders/slide3.jpg', 'button_text' => 'About Us', 'button_link' => '/about', 'sort_order' => 3],
        ];
        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
