@props(['label' => 'Label', 'name' => null, 'value' => '', 'disabled' => false])

@php
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
        @if($disabled) disabled @endif
        class="input"
    />
</div>
