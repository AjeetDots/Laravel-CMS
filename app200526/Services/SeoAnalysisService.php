<?php

namespace App\Services;

class SeoAnalysisService
{
    private const STOP_WORDS = [
        'a', 'an', 'the', 'and', 'or', 'but', 'in', 'on', 'at',
        'to', 'for', 'of', 'with', 'by', 'from', 'is', 'it',
    ];

    /**
     * Run Yoast-style analysis and return a score + checklist.
     *
     * @param  array{keyword:string, title:string, description:string, content:string, slug:string}  $params
     * @return array{score:int, rating:string, checks:array}
     */
    public function analyze(array $params): array
    {
        $keyword     = strtolower(trim($params['keyword'] ?? ''));
        $title       = strip_tags($params['title'] ?? '');
        $description = strip_tags($params['description'] ?? '');
        $content     = strip_tags($params['content'] ?? '');
        $slug        = $params['slug'] ?? '';

        $points = 0;
        $total  = 0;
        $checks = [];

        // ── 1. Focus keyword is set ────────────────────────────────────────
        $total += 5;
        if ($keyword) {
            $checks[] = $this->pass('Focus keyword is set', 5);
            $points   += 5;
        } else {
            $checks[] = $this->fail('No focus keyword set — add one above', 5);
            // Keyword-dependent checks are meaningless without it; bail early.
            return $this->result($checks, $points, $total + 65);
        }

        // ── 2. Keyword in meta title ───────────────────────────────────────
        $total += 15;
        if (str_contains(strtolower($title), $keyword)) {
            $checks[] = $this->pass('Keyword found in meta title', 15);
            $points   += 15;
        } else {
            $checks[] = $this->fail('Keyword missing from meta title — add it', 15);
        }

        // ── 3. Keyword in meta description ────────────────────────────────
        $total += 10;
        if (str_contains(strtolower($description), $keyword)) {
            $checks[] = $this->pass('Keyword in meta description', 10);
            $points   += 10;
        } else {
            $checks[] = $this->fail('Include the keyword in the meta description', 10);
        }

        // ── 4. Keyword in URL slug ────────────────────────────────────────
        $total += 10;
        $keywordSlug = str_replace(' ', '-', $keyword);
        if (str_contains(strtolower($slug), $keywordSlug)) {
            $checks[] = $this->pass('Keyword found in URL slug', 10);
            $points   += 10;
        } else {
            $checks[] = $this->warn('Keyword not in slug — consider renaming the URL', 10);
        }

        // ── 5. Keyword in first 300 chars of content ──────────────────────
        $total += 10;
        $intro = mb_substr($content, 0, 300);
        if (str_contains(strtolower($intro), $keyword)) {
            $checks[] = $this->pass('Keyword appears in the introduction', 10);
            $points   += 10;
        } else {
            $checks[] = $this->warn('Use the keyword within the first paragraph', 10);
        }

        // ── 6. Keyword density (0.5 % – 3 %) ─────────────────────────────
        $total    += 10;
        $wordCount = str_word_count($content);
        $kwWords   = str_word_count($keyword);
        $kwOccur   = substr_count(strtolower($content), $keyword);
        $density   = $wordCount > 0 ? round(($kwOccur * $kwWords / $wordCount) * 100, 2) : 0.0;

        if ($density >= 0.5 && $density <= 3.0) {
            $checks[] = $this->pass("Keyword density: {$density}% (target 0.5–3%)", 10);
            $points   += 10;
        } elseif ($density > 0) {
            $hint = $density < 0.5 ? 'Density too low — use the keyword more naturally.' : 'Keyword stuffing detected — reduce usage.';
            $checks[] = $this->warn("Keyword density: {$density}% — {$hint}", 10);
            $points   += 5;
        } else {
            $checks[] = $this->fail("Keyword not found in content body", 10);
        }

        // ── 7. Content length ─────────────────────────────────────────────
        $total += 15;
        if ($wordCount >= 600) {
            $checks[] = $this->pass("Content length: {$wordCount} words (excellent)", 15);
            $points   += 15;
        } elseif ($wordCount >= 300) {
            $checks[] = $this->warn("Content length: {$wordCount} words — aim for 600+", 15);
            $points   += 8;
        } else {
            $checks[] = $this->fail("Content too short: {$wordCount} words (minimum 300)", 15);
        }

        // ── 8. Meta title length (30–60 chars) ────────────────────────────
        $total    += 10;
        $titleLen  = mb_strlen($title);
        if ($titleLen >= 30 && $titleLen <= 60) {
            $checks[] = $this->pass("Meta title length: {$titleLen} chars (30–60 ✓)", 10);
            $points   += 10;
        } elseif ($titleLen > 0) {
            $hint = $titleLen < 30 ? 'Too short — expand the title.' : 'Too long — keep under 60 chars.';
            $checks[] = $this->warn("Meta title: {$titleLen} chars — {$hint}", 10);
            $points   += 4;
        } else {
            $checks[] = $this->fail('Meta title is empty', 10);
        }

        // ── 9. Meta description length (120–165 chars) ───────────────────
        $total   += 10;
        $descLen  = mb_strlen($description);
        if ($descLen >= 120 && $descLen <= 165) {
            $checks[] = $this->pass("Meta description: {$descLen} chars (120–165 ✓)", 10);
            $points   += 10;
        } elseif ($descLen > 0) {
            $hint = $descLen < 120 ? 'Too short — aim for at least 120 chars.' : 'Too long — trim to 165 chars.';
            $checks[] = $this->warn("Meta description: {$descLen} chars — {$hint}", 10);
            $points   += 4;
        } else {
            $checks[] = $this->fail('Meta description is empty', 10);
        }

        // ── 10. Slug cleanliness ──────────────────────────────────────────
        $total += 5;
        $slugParts   = array_filter(explode('-', $slug));
        $hasStop     = !empty(array_intersect($slugParts, self::STOP_WORDS));
        $tooLong     = strlen($slug) > 75;
        if (!$hasStop && !$tooLong && $slug !== '') {
            $checks[] = $this->pass('URL slug is clean and concise', 5);
            $points   += 5;
        } elseif ($tooLong) {
            $checks[] = $this->warn('Slug too long — shorten the URL', 5);
        } else {
            $checks[] = $this->warn('Remove stop words from slug (the, and, for…)', 5);
        }

        return $this->result($checks, $points, $total);
    }

    // ── Builder helpers ───────────────────────────────────────────────────────

    private function pass(string $label, int $weight): array
    {
        return ['label' => $label, 'status' => 'good', 'weight' => $weight];
    }

    private function warn(string $label, int $weight): array
    {
        return ['label' => $label, 'status' => 'ok', 'weight' => $weight];
    }

    private function fail(string $label, int $weight): array
    {
        return ['label' => $label, 'status' => 'poor', 'weight' => $weight];
    }

    private function result(array $checks, int $points, int $total): array
    {
        $score  = $total > 0 ? (int) round(($points / $total) * 100) : 0;
        $rating = match (true) {
            $score >= 80 => 'good',
            $score >= 50 => 'ok',
            default      => 'poor',
        };
        return compact('score', 'rating', 'checks');
    }
}
