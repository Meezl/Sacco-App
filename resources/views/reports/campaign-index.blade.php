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
        var options = {
            title: 'Campaign Response Statistics',
            pieSliceText: 'label',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);

    }

    function getData() {
        return [
            ['Resonse', 'count']
            @foreach($stats as $s) 
                ,["{{ $s['val'] }}", {{ $s['count'] }}]
            @endforeach
        ];
    }
}());

</script>
@stop