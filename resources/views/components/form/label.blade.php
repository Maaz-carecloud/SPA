@props([
    'for' => '',
    'required' => false,
    'class' => 'form-label',
])

<label 
    for="{{ $for }}" 
    {{ $attributes->merge(['class' => $class]) }}
>
    {{ $slot }}
    @if($required)
        <span class="text-danger">*</span>
    @endif
</label>
