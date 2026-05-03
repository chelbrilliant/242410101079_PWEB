@props([
    'judul'  => 'Label',
    'nilai'  => '0',
    'ikon'   => '📊',
    'warna'  => 'default'
])

@php
$styles = match($warna) {
    'success' => 'border-top: 4px solid #2ecc71; background: linear-gradient(135deg, #eafaf1, #ffffff);',
    'warning' => 'border-top: 4px solid #e67e22; background: linear-gradient(135deg, #fef9e7, #ffffff);',
    'danger'  => 'border-top: 4px solid #e74c3c; background: linear-gradient(135deg, #fdedec, #ffffff);',
    default   => 'border-top: 4px solid #2e86c1; background: linear-gradient(135deg, #eaf4fb, #ffffff);',
};
@endphp

<div class="bstat-card" style="{{ $styles }}">
  <div class="bstat-icon">{{ $ikon }}</div>
  <div class="bstat-num">{{ $nilai }}</div>
  <div class="bstat-label">{{ $judul }}</div>
</div>
