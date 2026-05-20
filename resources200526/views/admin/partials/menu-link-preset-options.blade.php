@foreach($menuLinkGroups ?? [] as $groupLabel => $links)
    <optgroup label="{{ $groupLabel }}">
        @foreach($links as $link)
            <option value="{{ $link['path'] }}">{{ $link['label'] }}</option>
        @endforeach
    </optgroup>
@endforeach
<option value="__custom__">Custom URL…</option>
