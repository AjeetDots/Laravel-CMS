@php
    use App\Support\HomeWhyCardIcons;

    $selected = (string) old($name, $value ?? '');
    $options = HomeWhyCardIcons::options();
    if ($selected !== '' && ! array_key_exists($selected, $options)) {
        $options = [$selected => $selected.' (custom)'] + $options;
    }
@endphp
<select name="{{ $name }}" @if(!empty($id)) id="{{ $id }}" @endif class="form-select">
    @foreach($options as $class => $label)
        <option value="{{ $class }}" @selected($selected === $class)>{{ $label }}</option>
    @endforeach
</select>
