@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'onLabel' => 'Active',
    'offLabel' => 'Inactive',
    'class' => '',
    'model' => null,
    'icon' => '',
])

<div class="mb-3">
    @if($label)
        <x-form.label 
            :for="$id ?: $name"
        >
            @if($icon)
                <i class="{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
        </x-form.label>
    @endif
    
    <div class="form-check form-switch">
        <input
            type="checkbox"
            @if($id) id="{{ $id }}" @else id="{{ $name }}" @endif
            @if($name) name="{{ $name }}" @endif
            @if($model) wire:model.live="{{ $model }}" @endif
            class="form-check-input {{ $class }} @error($name) is-invalid @enderror"
            value="1"
            @checked($checked)
            @disabled($disabled)
        >
        <label class="form-check-label" for="{{ $id ?: $name }}">
            {{ $checked ? $onLabel : $offLabel }}
        </label>
    </div>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
