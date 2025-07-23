@props([
    'label',
    'name',
    'model' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'rows' => 3,
    'icon' => '',
])

<div class="mb-3">
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
    
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($model) 
            wire:model.live.debounce.500ms="{{ $model }}" 
        @endif
        {{ $attributes->merge([
            'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid ' : '') . $class,
            'placeholder' => $placeholder,
        ])->when($required, fn($attrs) => $attrs->merge(['required' => true]))
          ->when($disabled, fn($attrs) => $attrs->merge(['disabled' => true])) }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
