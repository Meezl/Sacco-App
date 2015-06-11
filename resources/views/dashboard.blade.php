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
    Data Visualizations
</h2>
<div class="row">
    <div class="col-lg-6">
        <h3>Pie Chart</h3>
        <div id="pie_chart"></div>
    </div>
    <div class="col-lg-6">
        <h3>Bar Graph</h3>
        <div id="bar_graph"></div>
    </div>
</div>
<p id="loader" class="text-center"><span class="fa fa-spin fa-spinner fa-5x"></span></p>
@stop


@section('extra-scripts')
<script src="https://www.google.com/jsapi"></script>
<script>
    google.load('visualization', '1.0', {'packages': ['corechart']});

    google.setOnLoadCallback(drawChart);

    function drawChart() {
        //remove loading
        document.getElementById('loader').className = 'hidden';
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Members');
        data.addColumn('number', 'Number');
        data.addRows([
            ['Attending', 78],
            ['Not Attending', 12],
            ['Not Sure', 5],
            ['Unknown', 8]
        ]);

        var options = {
            'title': 'Monday 18th Attendance List',
            'width': 400,
            'height': 300
        };
        
        //pie chart
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        chart.draw(data, options);
        
        //bar graph
        var bar = new google.visualization.BarChart(document.getElementById('bar_graph'));
        bar.draw(data, options);
        
    }

</script>
@stop