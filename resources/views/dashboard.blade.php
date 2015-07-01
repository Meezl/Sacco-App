@extends('base.backend')

@section('title')
Dashboard
@stop

@section('content-title')
Dashboard
@stop



@section('extra-styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop


@section('inner-content')
<h2>
    <i class="fa fa-angle-right"></i>
    Meeting Attendance statistics
</h2>

<form action="{{ action('DashBoardController@postNewMeeting') }}" method="post" class="form" id="form-dashboard">
    <h4 class="text-center">Showing Responses received</h4>
    @if(count($errors))
    <p class="alert alert-danger">
        @foreach($errors as $r)
        {{ $r }}<br />
        @endforeach
    </p>
    @endif
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <label>From:</label>
                <input  value="{{ $meeting->start }}"  name="start" type="text" class="form-control date" />
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>To:</label>
                <input value="{{ $meeting->end }}" name="end" type="text" class="form-control date" />
            </div>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-theme">Show</button>
        </div>
    </div>
</form>

@if(!is_null($stats) && count($stats))

<p id="loader" class="text-center"><span class="fa fa-spin fa-spinner fa-5x"></span></p>

<div id="pie_chart" style="height: 400px"></div>
@else
<p class="alert alert-info">No User Response has arrived Yet</p>
@endif

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
                    var chart = new google.visualization.ColumnChart(document.getElementById('pie_chart'));
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
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
      $('.date').datepicker();
  </script>
@stop