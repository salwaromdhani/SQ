@props(['class' => ''])
<div {{ $attributes->merge(['class' => 'card-panel ' . $class]) }}>
    {{ $slot }}
</div>
