@php
$badges = [
    'pending' => 'badge-status badge-pending',
    'serving' => 'badge-status badge-serving',
    'completed' => 'badge-status badge-completed',
    'cancelled' => 'badge-status badge-cancelled',
];
$statusClass = $badges[$status ?? 'pending'] ?? 'badge-status bg-slate-100 text-slate-700';
$statusLabel = $label ?? ucfirst(str_replace('-', ' ', $status ?? 'pending'));
@endphp
<span class="{{ $statusClass }}">
    {{ $statusLabel }}
</span>
