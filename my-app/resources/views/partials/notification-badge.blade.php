{{--$count is passed explicitly from @includeWhen --}}
{{-- we dont use $notification->count() here - the parent already computed it --}}
<span class="inline-flex items-center justify-center bg-red-500 text-white text-xs font-bold min-w-[20px] h-5 px-1.5 rounded-full">
    {{$count}}
</span>