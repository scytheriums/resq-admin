@props([
    'class' => string::class,
    'label' => string::class,
])

<span class="badge bg-{{ $class }}">{{ $label }}</span>