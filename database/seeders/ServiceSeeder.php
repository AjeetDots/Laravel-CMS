<?php
namespace Database\Seeders;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder {
    public function run(): void {
        $services = [
            ['title' => 'Web Development', 'slug' => 'web-development', 'short_description' => 'Custom web solutions tailored to your business needs with modern technologies.', 'icon' => 'fas fa-code', 'sort_order' => 1],
            ['title' => 'Mobile Apps', 'slug' => 'mobile-apps', 'short_description' => 'Native and cross-platform mobile applications for iOS and Android.', 'icon' => 'fas fa-mobile-alt', 'sort_order' => 2],
            ['title' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'short_description' => 'Beautiful and intuitive designs that enhance user experience.', 'icon' => 'fas fa-paint-brush', 'sort_order' => 3],
            ['title' => 'Digital Marketing', 'slug' => 'digital-marketing', 'short_description' => 'Comprehensive digital marketing strategies to grow your online presence.', 'icon' => 'fas fa-chart-line', 'sort_order' => 4],
            ['title' => 'Cloud Solutions', 'slug' => 'cloud-solutions', 'short_description' => 'Scalable cloud infrastructure and deployment services.', 'icon' => 'fas fa-cloud', 'sort_order' => 5],
            ['title' => 'IT Consulting', 'slug' => 'it-consulting', 'short_description' => 'Expert technology consulting to optimize your business processes.', 'icon' => 'fas fa-lightbulb', 'sort_order' => 6],
        ];
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
