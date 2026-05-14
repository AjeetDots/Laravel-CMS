<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFooterNavigationRequest;
use App\Models\FooterNavColumn;
use App\Models\FooterNavLink;

class FooterNavigationController extends Controller
{
    public function edit()
    {
        $col1 = FooterNavColumn::query()->firstOrCreate(['slot' => 1], ['title' => 'Explore']);
        $col2 = FooterNavColumn::query()->firstOrCreate(['slot' => 2], ['title' => 'Company']);
        $links1 = FooterNavLink::query()->where('slot', 1)->orderBy('sort_order')->orderBy('id')->get();
        $links2 = FooterNavLink::query()->where('slot', 2)->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.footer-navigation.edit', compact('col1', 'col2', 'links1', 'links2'));
    }

    public function update(UpdateFooterNavigationRequest $request)
    {
        $v = $request->validated();

        FooterNavColumn::query()->updateOrCreate(['slot' => 1], ['title' => $v['slot_1_title']]);
        FooterNavColumn::query()->updateOrCreate(['slot' => 2], ['title' => $v['slot_2_title']]);

        $this->syncSlotLinks(1, $v['links_1'] ?? []);
        $this->syncSlotLinks(2, $v['links_2'] ?? []);

        return back()->with('success', 'Footer navigation saved.');
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     */
    private function syncSlotLinks(int $slot, array $rows): void
    {
        $normalized = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $label = trim((string) ($row['label'] ?? ''));
            $url = trim((string) ($row['url'] ?? ''));
            if ($label === '' && $url === '') {
                continue;
            }
            $id = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
            $normalized[] = [
                'id' => $id,
                'label' => $label !== '' ? $label : 'Link',
                'url' => $url !== '' ? $url : '#',
                'target' => (($row['target'] ?? '_self') === '_blank') ? '_blank' : '_self',
            ];
        }

        $incomingIds = collect($normalized)->pluck('id')->filter()->values()->all();
        if ($incomingIds !== []) {
            FooterNavLink::query()->where('slot', $slot)->whereNotIn('id', $incomingIds)->delete();
        } else {
            FooterNavLink::query()->where('slot', $slot)->delete();
        }

        foreach ($normalized as $ord => $r) {
            if ($r['id']) {
                FooterNavLink::query()
                    ->where('slot', $slot)
                    ->whereKey($r['id'])
                    ->update([
                        'label' => $r['label'],
                        'url' => $r['url'],
                        'target' => $r['target'],
                        'sort_order' => $ord,
                        'is_active' => true,
                    ]);
            } else {
                FooterNavLink::query()->create([
                    'slot' => $slot,
                    'label' => $r['label'],
                    'url' => $r['url'],
                    'target' => $r['target'],
                    'sort_order' => $ord,
                    'is_active' => true,
                ]);
            }
        }
    }
}
