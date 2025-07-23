@props([
    'label',
    'id',
    'name' => null,
    'options' => [],
    'model' => null,
    'value' => '',
    'placeholder' => 'Select Options',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'containerClass' => 'mb-3',
    'multiple' => false,
    'icon' => '',
])

@php
    // Use name if provided, otherwise use id
    $elementName = $name ?? $id;
    
    // Extract wire:model from attributes if model is not explicitly provided
    $wireModel = $model;
    if (!$wireModel && $attributes->has('wire:model')) {
        $wireModel = $attributes->get('wire:model');
    }
    if (!$wireModel && $attributes->has('wire:model.lazy')) {
        $wireModel = $attributes->get('wire:model.lazy');
    }
    if (!$wireModel && $attributes->has('wire:model.defer')) {
        $wireModel = $attributes->get('wire:model.defer');
    }
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <x-form.label :for="$elementName" :required="$required">
            @if($icon)
                <i class="{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
        </x-form.label>
    @endif
    <div wire:ignore>
        <select 
            {{ $attributes->except(['wire:model', 'wire:model.lazy', 'wire:model.defer']) }} 
            id="{{ $id }}" 
            name="{{ $elementName }}"
            @if($multiple) multiple="multiple" @endif
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            data-placeholder="{{ $placeholder }}" 
            class="form-select form-select-sm select2 {{ $class }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            style="width: 100%;"
        >
            @if(!$multiple && $placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" 
                    {{ ($value == $optionValue || (is_array($value) && in_array($optionValue, $value))) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
    
    @if($wireModel && $errors->has($wireModel))
        <div class="invalid-feedback d-block">
            {{ $errors->first($wireModel) }}
        </div>
    @endif
</div>

@once
@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap-5-theme.min.css') }}">
<style>
/* Custom validation styles for select2 */
.select2-container .select2-selection--single.is-invalid,
.select2-container .select2-selection--multiple.is-invalid {
    border-color: #dc3545 !important;
}

.select2-container .select2-selection--single.is-valid,
.select2-container .select2-selection--multiple.is-valid {
    border-color: #198754 !important;
}

.invalid-feedback.d-block {
    display: block !important;
}
</style>
@endpush
@endonce

@once
@push('scripts')
<!-- Select2 -->
<script src="{{ asset('assets/js/select2.full.min.js') }}"></script>
@endpush
@endonce

@push('scripts')
<script>
$(document).ready(function() {
    const elementId = '{{ $id }}';
    const wireModel = '{{ $wireModel }}';
    const isMultiple = {{ $multiple ? 'true' : 'false' }};
    const placeholder = '{{ $placeholder }}';
    
    function initSelect2() {
        const $element = $('#' + elementId);
        if ($element.length) {
            
            // Destroy existing instance if any
            if ($element.hasClass('select2-hidden-accessible')) {
                $element.select2('destroy');
            }
            
            // Initialize select2 with proper configuration
            $element.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: placeholder || 'Select an option',
                allowClear: !isMultiple && placeholder !== '', // Allow clear for single select with placeholder
                multiple: isMultiple,
                dropdownParent: $element.closest('.modal').length ? $element.closest('.modal') : $('body')
            });
            
            // Handle change events
            $element.off('change.select2Component').on('change.select2Component', function(e) {
                const selectedValue = $(this).val();
                
                // Update Livewire model if wire:model is specified
                if (wireModel && wireModel.trim() !== '') {
                    // Handle different wire:model types
                    const cleanModel = wireModel.replace(/^(lazy|defer):/, '');
                    @this.set(cleanModel, selectedValue);
                }
                
                // Update validation styling
                updateValidationStyling();
            });
        }
    }
    
    // Function to update validation styling
    function updateValidationStyling() {
        const $element = $('#' + elementId);
        
        // Handle empty containerClass by finding the closest parent div
        const containerClass = '{{ trim($containerClass) }}';
        const $container = containerClass ? $element.closest('.' + containerClass) : $element.closest('div');
        
        const hasError = $container.find('.invalid-feedback').length > 0 && $container.find('.invalid-feedback').text().trim() !== '';
        const $select2Selection = $element.next('.select2-container').find('.select2-selection');
        
        if (hasError) {
            $select2Selection.addClass('is-invalid').removeClass('is-valid');
        } else {
            $select2Selection.removeClass('is-invalid');
        }
    }
    
    // Initialize select2 immediately
    setTimeout(initSelect2, 100);
    
    // Initialize when modal is shown
    $(document).on('shown.bs.modal', function() {
        setTimeout(initSelect2, 200);
    });
    
    // Listen for Livewire updates
    document.addEventListener('livewire:updated', function() {
        setTimeout(function() {
            initSelect2();
            updateValidationStyling();
        }, 100);
    });
    
    // Initial validation styling update
    setTimeout(updateValidationStyling, 300);
});
</script>
@endpush
