@props([
    'color' => 'gray', // green, red, yellow, blue, gray
])

@php
$colorClasses = [
    'green'  => 'bg-green-100 text-green-700 ring-green-200',
    'red'    => 'bg-red-100 text-red-700 ring-red-200',
    'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
    'blue'   => 'bg-blue-100 text-blue-700 ring-blue-200',
    'gray'   => 'bg-slate-100 text-slate-600 ring-slate-200',
];
$classes = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$classes}"
]) }}>
    {{ $slot }}
</span>