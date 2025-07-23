@props([
    'buyingPrice' => 0,
    'sellingPrice' => 0,
])

@php
    $profit = $sellingPrice - $buyingPrice;
    $margin = $buyingPrice > 0 ? (($profit / $buyingPrice) * 100) : 0;
    $isPositive = $profit >= 0;
@endphp

<div class="mb-3">
    <label class="form-label fw-semibold text-muted">
        <i class="fas fa-info-circle me-1"></i>Profit Margin
    </label>
    <div class="alert {{ $buyingPrice > 0 && $sellingPrice > 0 ? ($isPositive ? 'alert-success' : 'alert-danger') : 'alert-info' }} py-2 px-3">
        @if($buyingPrice > 0 && $sellingPrice > 0)
            <small class="{{ $isPositive ? 'text-success' : 'text-danger' }} fw-semibold">
                <i class="fas {{ $isPositive ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                Profit: Rs.{{ number_format($profit, 2) }} ({{ number_format($margin, 2) }}%)
            </small>
        @else
            <small>
                <i class="fas fa-calculator me-1"></i>Enter prices to see profit margin
            </small>
        @endif
    </div>
</div>
