<?php
namespace Database\Seeders;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder {
    public function run(): void {
        $items = [
            ['name' => 'home', 'label' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['name' => 'services', 'label' => 'Services', 'url' => '/services', 'sort_order' => 2],
            ['name' => 'gallery', 'label' => 'Gallery', 'url' => '/gallery', 'sort_order' => 3],
            ['name' => 'about', 'label' => 'About Us', 'url' => '/about', 'sort_order' => 4],
            ['name' => 'contact', 'label' => 'Contact Us', 'url' => '/contact', 'sort_order' => 5],
        ];
        foreach ($items as $item) {
            Menu::create($item);
        }
        $support = Menu::create(['name' => 'support', 'label' => 'Support', 'url' => '#', 'sort_order' => 6]);
        Menu::create(['name' => 'faq', 'label' => 'FAQ', 'url' => '/faq', 'parent_id' => $support->id, 'sort_order' => 1]);
        Menu::create(['name' => 'documentation', 'label' => 'Documentation', 'url' => '/docs', 'parent_id' => $support->id, 'sort_order' => 2]);
        Menu::create(['name' => 'helpdesk', 'label' => 'Help Desk', 'url' => '/help', 'parent_id' => $support->id, 'sort_order' => 3]);
    }
}
