@props(['id', 'label', 'color'=>'#378ADD'])
{{-- @once + @push together = the perfect pattern --}}
{{-- this script tag will appear only ONCE in the final html --}}
@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            //Shared chart initializer - also runs once
            window.initChart = function(id, label,color, data) {
                new Chart(document.getElementById(id), {
                    type: 'line',
                    data: {
                        labels:['Jan','Feb','Mar','Apr','May'],
                        datasets: [{label: label, data: data, borderColor: color }]
                    }
                });
            }
        </script>
    @endpush
@endonce

{{-- this canvas renders every time - one per component use--}}
<canvas id="{{$id}}" width="400" height="200"></canvas>

{{-- Each component pushes its own init call - this is not inside @once --}}    
@push('scripts')
   
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.initChart(
                '{{$id}}',
                '{{$label}}',
                '{{$color}}',
                [{{implode(',', $data ?? [12,19,8,25,14]) }}]
            );
        });
    </script>
@endpush
