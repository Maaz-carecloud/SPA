@props([
    'label',
    'id',
    'name',
    'value' => '',
    'model' => null,
    'checked' => false,
    'disabled' => false,
    'class' => 'form-check-input',
    'labelClass' => 'form-check-label',
    'containerClass' => 'form-check',
])

<div class="{{ $containerClass }}">
    <input 
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @if($model) 
            wire:model="{{ $model }}" 
        @endif
        {{ $attributes->merge([
            'class' => $class . ($errors->has($name) ? ' is-invalid' : ''),
        ])->when($checked, fn($attrs) => $attrs->merge(['checked' => true]))
          ->when($disabled, fn($attrs) => $attrs->merge(['disabled' => true])) }}
    >
    
    @if($label)
        <label 
            class="{{ $labelClass }}" 
            for="{{ $id }}"
        >
            {{ $label }}
        </label>
    @endif
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
