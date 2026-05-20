<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\Page;

trait ValidatesPageSectionImages
{
    protected function validatePageSectionImages($validator): void
    {
        $template = (string) $this->input('template', '');
        $page = $this->route('page');
        if ($page instanceof Page) {
            $template = $page->template;
        }

        if (! in_array($template, Page::sectionedTemplates(), true)) {
            return;
        }

        $sections = $this->input('sections', []);
        if (! is_array($sections)) {
            return;
        }

        foreach ($sections as $index => $section) {
            if (! is_array($section)) {
                continue;
            }

            $existing = trim((string) ($section['existing_image'] ?? ''));
            $hasUpload = $this->hasFile("sections.{$index}.image");

            if ($existing === '' && ! $hasUpload) {
                $validator->errors()->add(
                    "sections.{$index}.image",
                    'Each page section must include an image.'
                );
            }
        }
    }
}
