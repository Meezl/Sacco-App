@extends('base.backend')

@section('title')
Dashboard
@stop

@section('content-title')
Dashboard
@stop



@section('inner-content')
<h2>
    <i class="fa fa-angle-right"></i>
    June 26 Class A Attendance statistics
</h2>

<p id="loader" class="text-center"><span class="fa fa-spin fa-spinner fa-5x"></span></p>

<div id="pie_chart" style="height: 400px"></div>


@stop


@section('extra-scripts')
<script src="https://www.google.com/jsapi"></script>
<script>
            google.load('visualization', '1.0', {'packages': ['corechart']});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
            var loader = document.getElementById('loader');
                    loader.className = 'hidden';
                    var data = google.visualization.arrayToDataTable(getData());
                    
                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                        {
                            calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation"
                        },
                        2]);
                    var options = {title: 'Members\' Responses',
                    legend: {position: 'none'}};
                    var chart = new google.visualization.BarChart(document.getElementById('pie_chart'));
                    chart.draw(view, options);
            }

    function getData() {
    return [
            ['Response', 'count', {role: 'style'}]
            @for($i = 0; $i < count($stats); $i++)
            ,["{{ $stats[$i]->text}}", {{ $stats[$i]->count }}, "{{$colors[$i]}}"]
            @endfor            
    ];
    }
</script>
@stop