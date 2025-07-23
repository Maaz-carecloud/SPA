@props([
    'title',
    'icon' => '',
    'class' => 'mb-4',
])

<div class="{{ $class }}">
    <div class="col-12">
        <h6 class="text-muted border-bottom pb-2 mb-3">
            @if($icon)
                <i class="{{ $icon }} me-2"></i>
            @endif
            {{ $title }}
        </h6>
    </div>
    {{ $slot }}
</div>
