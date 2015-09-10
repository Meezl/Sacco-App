@extends('base.backend')

@section('title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('content-title')
{{ $campaign->getIdString() }} - {{ $campaign->title }}
@stop

@section('inner-content')
<a class="pull-right btn btn-default btn-xs" href="{{action('StatsController@getReport', [$campaign->getIdString()])}}" target="_blank">Print</a>
<div class="clearfix"></div>
<ul>
    <li><b>Code: </b>{{ $campaign->getIdString() }}</li>
    <li><b>Members Contacted:</b>{{ $campaign->total_contacted }} </li>
    <li><b>Estimated Cost Incurred:</b> {{ $campaign->cost }}</li>
    <li><b>Responses: </b><a href="{{ action('StatsController@getResponses', [$campaign->getIdString()]) }}">View {{ $campaign->total_responses }} Responses</a></li>
</ul>

@if($campaign->possible_responses)
<br>
<p id="loader"  class="text-center">
   <span class="fa fa-spinner fa-spin fa-3x"></span> 
</p>

<div id="chart" style="height: 500px"></div>
<br />
<div class="container">
    <h3>Tabular Data</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Option</td>
                    <td>Text</td>
                    <td>Count</td>
                    <td>%</td>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < count($stats); $i++)
                <tr>
                    <td>{{$i+1}}</td>
                    <td>{{$stats[$i]->key}}</td>
                    <td>{{$stats[$i]->val}}</td>
                    <td>{{$stats[$i]->count}}</td>
                    <td>{{number_format($stats[$i]->percent, 2)}}%</td>
                </tr>
                @endfor
                <tr>
                    <td></td>
                    <td colspan="2"><b>Total Responses</b></td>
                    <td colspan="2">{{ $campaign->total_responses }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif

<h3>Actual Text Sent</h3>
<div class="well well-lg">
    {!! nl2br(htmlentities($campaign->getSms())) !!}
</div>


<h3>Description:</h3>
<p>
    {{ $campaign->description }}
</p>
<br />


@stop

@section('extra-scripts')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
    //more space for the charts
    hideMenu();
    
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
        var formatter = new google.visualization.NumberFormat({suffix: '%'});
        formatter.format(data, 1);
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
        var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
        chart.draw(view, options);

    }

    function getData() {        
        return [
            ['Response', 'count', {role: 'style'}]
            @for($i = 0; $i < count($stats); $i++)
            ,["{{ $stats[$i]->val}}", {{ $stats[$i]->percent }}, "{{$colors[$i]}}"]
            @endfor            
    ];
    }
}());

</script>
@stop