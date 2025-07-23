@props([
    'label',
    'name',
    'id' => null,
    'type' => 'text',
    'model' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'class' => '',
    'labelClass' => 'form-label',
    'containerClass' => 'mb-3',
    'help' => null,
])

@php
    // Handle both regular form names and Livewire dot notation
    $errorKey = $name;
    $hasError = $errors->has($errorKey);
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <x-form.label 
            :for="$id ?? $name" 
            :required="$required"
            :class="$labelClass"
        >
            {{ $label }}
        </x-form.label>
    @endif
    
    <input
        type="{{ $type }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        @if($model) 
            wire:model{{ str_contains($model, '.') ? (str_contains($model, 'lazy') ? '.lazy' : (str_contains($model, 'defer') ? '.defer' : '')) : '' }}="{{ str_replace(['lazy:', 'defer:'], '', $model) }}" 
        @endif
        {{ $attributes->merge([
            'class' => 'form-control form-control-sm ' . ($hasError ? 'is-invalid ' : '') . $class,
            'placeholder' => $placeholder,
            'value' => old($name, $value),
        ])->when($disabled, fn($attrs) => $attrs->merge(['disabled' => true]))
          ->when($readonly, fn($attrs) => $attrs->merge(['readonly' => true])) }}
    >
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($errorKey)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
