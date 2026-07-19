@props([
    'type'=>'info'
])

<span class="badge badge-{{$type}}">
    {{$slot}}
</span>