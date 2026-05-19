@props([
    'src' => null,
    'alt' => '',
])

<img {{ $attributes->merge(['src' => \App\Support\CmsImage::resolve($src), 'alt' => $alt]) }}>
