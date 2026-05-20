<?php

namespace App\Support;

/**
 * Regional-indicator flag emoji from ISO 3166-1 alpha-2 (no external assets).
 */
final class CountryFlag
{
    public static function fromIso(?string $iso): string
    {
        $iso = strtoupper(preg_replace('/[^a-zA-Z]/', '', (string) $iso));
        if (strlen($iso) !== 2) {
            return '';
        }
        $a = ord($iso[0]);
        $b = ord($iso[1]);
        if ($a < 65 || $a > 90 || $b < 65 || $b > 90) {
            return '';
        }
        $cpA = 0x1F1E6 + ($a - 65);
        $cpB = 0x1F1E6 + ($b - 65);

        return mb_chr($cpA, 'UTF-8').mb_chr($cpB, 'UTF-8');
    }

    public static function display(?string $storedEmoji, ?string $iso): string
    {
        $e = trim((string) $storedEmoji);
        if ($e !== '') {
            return $e;
        }

        return self::fromIso($iso) ?: '🌐';
    }

    /**
     * Tiny SVG (emoji inside &lt;text&gt;) as a data: URL so flags render like intl-tel-input
     * even when plain text would show regional letters (e.g. "GB") or empty boxes.
     */
    public static function svgDataUrl(string $emojiChar): string
    {
        $emojiChar = trim($emojiChar) !== '' ? $emojiChar : '🌐';
        $safe = htmlspecialchars($emojiChar, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<svg xmlns="http://www.w3.org/2000/svg" width="28" height="20" viewBox="0 0 28 20">'
            .'<text x="3" y="15" font-size="12" font-family="Segoe UI Emoji,Apple Color Emoji,Noto Color Emoji,Noto Emoji,sans-serif">'.$safe.'</text>'
            .'</svg>';

        return 'data:image/svg+xml;charset=utf-8,'.rawurlencode($svg);
    }

    public static function svgDataUrlFor(?string $storedEmoji, ?string $iso): string
    {
        $lc = strtolower(preg_replace('/[^a-zA-Z]/', '', (string) $iso));
        if (strlen($lc) === 2) {
            return 'https://flagcdn.com/w20/' . $lc . '.png';
        }

        return self::svgDataUrl(self::display($storedEmoji, $iso));
    }
}
