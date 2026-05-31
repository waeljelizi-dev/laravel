@props([
    'label' => null,
    'error' => null
])

<div class="mb-4">
    @if($label)
        <label class="block text-sm font-medium mb-1">{{$label}}</label>
    @endif
    {{-- $attributes catches: name, id, type, placeholder, wire:model, x-model, :value, @change, any Alpine or livewire attribute or everything--}}
    <input
        {{$attributes->merge([
            'class' => 'w-full border rouded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500'
            .($error ? 'border-red-500': 'border-gray-300')
           ])
        }}
    />
    @if($error)
        <p class="text-red-500 text-xs mt-1">{{$error}}</p>
    @endif
</div>