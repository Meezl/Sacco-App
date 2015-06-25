@extends('base.backend')

@section('title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('content-title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('inner-content')
<ul>
    <li><b>Code: </b>{{ $campaign->getIdString() }}</li>
    <li><b>Members Contacted:</b>{{ $campaign->total_contacted }} </li>
    <li><b>Estimated Cost Incurred:</b> {{ $campaign->cost }}</li>
    <li><b>Responses: </b><a href="{{ action('StatsController@getResponses', [$campaign->getIdString()]) }}">View {{ $campaign->total_responses }} Campaign Responses</a></li>
</ul>
<h3>Actual Text Sent</h3>
<div class="well well-lg">
    {!! nl2br(htmlentities($campaign->getSms())) !!}
</div>
@if($campaign->possible_responses)
<br>
<p id="loader"  class="text-center">
   <span class="fa fa-spinner fa-spin fa-3x"></span> 
</p>

<div id="chart" style="height: 300px"></div>
<br />
@endif
<h3>Description:</h3>
<p>
    {{ $campaign->description }}
</p>
<br />


@stop

@section('extra-scripts')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
(function() {
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var draw = {{ $campaign->possible_responses ? 'true': 'false' }};
        
        if (!draw) {
            return;
        }
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
                    
        var options = {title: 'Campaign Response Statistics',
        legend: {position: 'none'}};
        var chart = new google.visualization.BarChart(document.getElementById('chart'));
        chart.draw(view, options);

    }

    function getData() {        
        return [
            ['Response', 'count', {role: 'style'}]
            @for($i = 0; $i < count($stats); $i++)
            ,["{{ $stats[$i]->val}}", {{ $stats[$i]->count }}, "{{$colors[$i]}}"]
            @endfor            
    ];
    }
}());

</script>
@stop