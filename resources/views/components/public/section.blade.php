@props([
    'class' => '',
    'id' => null,
])
<section {{ $id ? "id=\"$id\"" : '' }} {{ $attributes->merge(['class' => 'py-16 ' . $class]) }}>
    {{ $slot }}
</section>