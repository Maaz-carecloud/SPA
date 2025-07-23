@props([
    'label',
    'name',
    'type' => 'number',
    'model' => null,
    'step' => '0.01',
    'prefix' => 'Rs.',
    'placeholder' => '0.00',
    'required' => false,
    'icon' => '',
    'class' => '',
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
    
    <div class="input-group">
        <span class="input-group-text">{{ $prefix }}</span>
        <input
            type="{{ $type }}"
            step="{{ $step }}"
            id="{{ $name }}"
            name="{{ $name }}"
            @if($model) 
                wire:model.live.debounce.500ms="{{ $model }}"
            @endif
            {{ $attributes->merge([
                'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid ' : '') . $class,
                'placeholder' => $placeholder,
            ])->when($required, fn($attrs) => $attrs->merge(['required' => true])) }}
        >
    </div>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
