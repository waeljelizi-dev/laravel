@props([
    'type' => 'info',
    'dismissible' => false
])

@php 
    $styles = [
        'info' => 'bg-blue-50 border-blue-400 text-blue-800',
        'success' => 'bg-green-50 border-green-400 text-green-800',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
        'danger' => 'bg-red-50 border-red-400 text-red-800'
    ];

    $icons = [
        'info' => '💬',
        'success' => '✅',
        'warning' => '⚠️',
        'danger'=> '🔴'
    ];

    $style = $styles[$type] ?? $styles['info'];
    $icon = $icons[$type] ?? $icons['info'];
    $base = 'border-1-4 p-4 rounded-r-lg';
    $classes = $base .' '. $style;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-start gap-3">
        <span class="text-lg">{{$icon}}</span>
        <div class="flex-1">
            {{-- Named slot: title - only renders if provided --}}
            @if(isset($title))
                <h4 class="font-semibold mb-1">{{$title}}</h4>
            @endif
            {{-- Default slot: the message body --}}
            <p class="text-sm">{{$slot}}</p>
        </div>
        {{-- Optional dismiss button  --}}
        @if($dismissible)
            <button onclick="this.closest('div[class]').remove()"
                class="text-lg opacity-50 hover:opacity-100"
            >
                ✕
            </button>
        @endif
    </div>
</div>