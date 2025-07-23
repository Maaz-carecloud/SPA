@props([
    'label',
    'name',
    'model' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'rows' => 5,
    'icon' => '',
])

<div class="mb-3 position-relative" wire:ignore>
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
            x-data 
            x-init="
                window.addEventListener('livewire:navigated', () => { 
                    if(window.CKEDITOR_INSTANCES && window.CKEDITOR_INSTANCES['{{ $name }}']) 
                        window.CKEDITOR_INSTANCES['{{ $name }}'].destroy(); 
                    ClassicEditor.create($el).then(editor => { 
                        window.CKEDITOR_INSTANCES = window.CKEDITOR_INSTANCES || {}; 
                        window.CKEDITOR_INSTANCES['{{ $name }}'] = editor; 
                        editor.model.document.on('change:data', () => { 
                            $dispatch('input', editor.getData()); 
                        }); 
                        Livewire.on('refreshCkeditor', () => { 
                            editor.setData(@this.get('{{ $model }}')); 
                        }); 
                    }); 
                })" 
            wire:model.live.debounce.500ms="{{ $model }}" 
        @endif
        {{ $attributes->merge([
            'class' => 'form-control ckeditor ' . ($errors->has($name) ? 'is-invalid ' : '') . $class,
            'placeholder' => $placeholder,
        ])->when($required, fn($attrs) => $attrs->merge(['required' => true]))
          ->when($disabled, fn($attrs) => $attrs->merge(['disabled' => true])) }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script data-navigate-once src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
@endpush
