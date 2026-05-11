@php
$types = [
    'success' => 'bg-emerald-50 border-emerald-100 text-emerald-700',
    'warning' => 'bg-amber-50 border-amber-100 text-amber-800',
    'danger' => 'bg-rose-50 border-rose-100 text-rose-700',
    'info' => 'bg-[#FEE2E2] border-[#F4CDD1] text-[#991B1B]',
];
$alertClass = $types[$type ?? 'info'] ?? $types['info'];
@endphp
<div class="rounded-3xl border px-4 py-4 text-sm shadow-sm {{ $alertClass }}">
    @if(!empty($title))
        <p class="font-semibold">{{ $title }}</p>
    @endif
    <div class="mt-1">{{ $message ?? $slot }}</div>
</div>
