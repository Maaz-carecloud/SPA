@props([
    'label',
    'name',
    'options' => [],
    'model' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'containerClass' => 'mb-3',
    'icon' => '',
])

<div class="{{ $containerClass }}">
    @if($label)
        <x-form.label 
            :for="$name" 
            :required="$required"
        >
            @if($icon)
                <i class="{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
        </x-form.label>
    @endif
    
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($model) 
            wire:model{{ str_contains($model, '.') ? (str_contains($model, 'lazy') ? '.lazy' : (str_contains($model, 'defer') ? '.defer' : '.live')) : '.live' }}="{{ str_replace(['lazy:', 'defer:'], '', $model) }}" 
        @endif
        {{ $attributes->merge([
            'class' => 'form-select form-select-sm ' . ($errors->has($name) ? 'is-invalid ' : '') . $class,
        ])->when($required, fn($attrs) => $attrs->merge(['required' => true]))
          ->when($disabled, fn($attrs) => $attrs->merge(['disabled' => true])) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
