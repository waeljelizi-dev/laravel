@props(['padding' => true])
<div {{$attributes->merge(['class' => 'mb-3 bg-white rounder-xl border-gray-200 shadow-sm overflow-hidden']) }}>
    {{-- header slot - only renders the header section -if content was provided --}}
    @if(isset($header))
        <div class="px-5 py-4 border-b border-gray-100 font-semibold text-gray-800">
            {{$header}}
        </div>
    @endif

    {{-- Default slot - the body card --}}
    <div class="{{$padding ? 'p-5': ''}}">
        {{$slot}}
    </div>

    {{-- Footer slot - only renders if content was provided --}}
    @if(isset($footer))
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 text-sm text-gray-800">
            {{$footer}}
        </div>
    @endif
</div>