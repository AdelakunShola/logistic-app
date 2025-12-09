@props(['label' => 'Label', 'name' => null, 'value' => ''])

@php
    // If label or name not provided, make safe fallbacks
    $safeLabel = $label ?? 'Label';
    $generatedName = $name ?? str_replace(' ', '_', strtolower($safeLabel));
    $id = $generatedName;
@endphp

<div>
    <label class="text-sm font-medium" for="{{ $id }}">{{ $safeLabel }}</label>
    <input
        id="{{ $id }}"
        type="text"
        name="{{ $generatedName }}"
        value="{{ old($generatedName, $value) }}"
        class="input"
    />
</div>
