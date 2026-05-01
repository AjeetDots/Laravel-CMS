<?php
namespace Database\Seeders;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder {
    public function run(): void {
        $testimonials = [
            ['client_name' => 'John Anderson', 'client_position' => 'CEO', 'client_company' => 'TechCorp', 'message' => 'Outstanding service! The team delivered our project on time and exceeded our expectations. Highly recommended for any web development needs.', 'rating' => 5, 'sort_order' => 1],
            ['client_name' => 'Sarah Mitchell', 'client_position' => 'Marketing Director', 'client_company' => 'BrandHouse', 'message' => 'Professional, skilled, and incredibly responsive. They transformed our outdated website into a modern, high-converting platform.', 'rating' => 5, 'sort_order' => 2],
            ['client_name' => 'Robert Chen', 'client_position' => 'Founder', 'client_company' => 'StartupXYZ', 'message' => 'The mobile app they built for us has been a game changer. Smooth, fast, and exactly what we envisioned. Great team to work with!', 'rating' => 5, 'sort_order' => 3],
        ];
        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
