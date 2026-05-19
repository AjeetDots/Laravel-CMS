<?php

declare(strict_types=1);

namespace App\Support;

final class HomeWhyCardIcons
{
    /**
     * Font Awesome class (without fa-solid prefix) => admin label.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            'fa-award' => 'Master Craftsmanship (award)',
            'fa-palette' => 'Bespoke by Design (palette)',
            'fa-clapperboard' => 'Trusted by Productions (clapperboard)',
            'fa-leaf' => 'Considered Materials (leaf)',
            'fa-hammer' => 'Hammer',
            'fa-paintbrush' => 'Paintbrush',
            'fa-brush' => 'Brush',
            'fa-hand-sparkles' => 'Hand sparkles',
            'fa-gem' => 'Gem',
            'fa-star' => 'Star',
            'fa-shield-halved' => 'Shield',
            'fa-certificate' => 'Certificate',
            'fa-building-columns' => 'Building columns',
            'fa-house' => 'House',
            'fa-ruler-combined' => 'Ruler',
            'fa-compass-drafting' => 'Drafting compass',
            'fa-lightbulb' => 'Lightbulb',
            'fa-recycle' => 'Recycle',
            'fa-seedling' => 'Seedling',
            'fa-hand-holding-heart' => 'Hand holding heart',
            'fa-users' => 'Users',
            'fa-camera' => 'Camera',
            'fa-film' => 'Film',
            'fa-clock' => 'Clock',
            'fa-check' => 'Check',
        ];
    }

    /**
     * Icons that render as custom PNGs on the home page (others use Font Awesome).
     *
     * @return array<string, string>
     */
    public static function imageMap(): array
    {
        return [
            'fa-award' => asset('images/master-craftmanship.png'),
            'fa-palette' => asset('images/bespoke-design.png'),
            'fa-clapperboard' => asset('images/trusted.png'),
            'fa-leaf' => asset('images/considered.png'),
        ];
    }

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::options());
    }
}
