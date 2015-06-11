@extends('base.backend')

@section('title')
Campaigns
@stop

@section('content-title')
Campaigns
@stop

@section('inner-content')
<div class="row">
    <div class="col-md-4 mb">
        <div class="darkblue-panel pn">
            <div class="darkblue-header">
                <h5>18th Meeting STATISTICS</h5>
            </div>
            <canvas width="120" height="120" id="serverstatus02" style="width: 120px; height: 120px;"></canvas>            
            <p>June 18, 2015</p>
            <footer>
                <div class="pull-left">
                    <h5>120 Invited</h5>
                </div>
                <div class="pull-right">
                    <h5>60% Attending</h5>
                </div>
            </footer>
        </div><!-- -- /darkblue panel ---->
    </div>

    <div class="col-md-4 col-sm-4 mb">
        <div class="white-panel pn donut-chart">
            <div class="white-header">
                <h5>Savings</h5>
            </div>
            <div class="row">
                <div class="col-sm-6 col-xs-6 goleft">
                    <p><i class="fa fa-database"></i> 70%</p>
                </div>
            </div>
            <canvas width="120" height="120" id="serverstatus01" style="width: 120px; height: 120px;"></canvas>           
        </div><!-- --/grey-panel ---->
    </div>


    <div class="col-md-4 col-sm-4 mb">
        <!-- REVENUE PANEL -->
        <div class="darkblue-panel pn">
            <div class="darkblue-header">
                <h5>REVENUE</h5>
            </div>
            <div class="chart mt">
                <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="4" data-data="[200,135,667,333,526,996,564,123,890,464,655]"></div>
            </div>
            <p class="mt"><b>$ 17,980</b><br/>Month Income</p>
        </div>
    </div><!-- /col-md-4 -->
</div>
<div class="row mt">
    <!--CUSTOM CHART START -->
    <div class="border-head">
        <h3>Responses</h3>
    </div>
    <div class="custom-bar-chart">
        <ul class="y-axis">
            <li><span>10.000</span></li>
            <li><span>8.000</span></li>
            <li><span>6.000</span></li>
            <li><span>4.000</span></li>
            <li><span>2.000</span></li>
            <li><span>0</span></li>
        </ul>
        <div class="bar">
            <div class="title">JAN</div>
            <div class="value tooltips" data-original-title="8.500" data-toggle="tooltip" data-placement="top">85%</div>
        </div>
        <div class="bar ">
            <div class="title">FEB</div>
            <div class="value tooltips" data-original-title="5.000" data-toggle="tooltip" data-placement="top">50%</div>
        </div>
        <div class="bar ">
            <div class="title">MAR</div>
            <div class="value tooltips" data-original-title="6.000" data-toggle="tooltip" data-placement="top">60%</div>
        </div>
        <div class="bar ">
            <div class="title">APR</div>
            <div class="value tooltips" data-original-title="4.500" data-toggle="tooltip" data-placement="top">45%</div>
        </div>
        <div class="bar">
            <div class="title">MAY</div>
            <div class="value tooltips" data-original-title="3.200" data-toggle="tooltip" data-placement="top">32%</div>
        </div>
        <div class="bar ">
            <div class="title">JUN</div>
            <div class="value tooltips" data-original-title="6.200" data-toggle="tooltip" data-placement="top">62%</div>
        </div>
        <div class="bar">
            <div class="title">JUL</div>
            <div class="value tooltips" data-original-title="7.500" data-toggle="tooltip" data-placement="top">75%</div>
        </div>
    </div>
    <!--custom chart end-->
</div><!-- /row -->	
@stop


@section('extra-scripts')
<script src="{{ asset('assets/js/chart-master/Chart.js') }}"></script>
<script src="{{ asset('assets/js/sparkline-chart.js') }}"></script>
<script>
var doughnutData = [
    {
        value: 60,
        color: "#68dff0"
    },
    {
        value: 40,
        color: "#444c57"
    }
];
var myDoughnut = new Chart(document.getElementById("serverstatus02").getContext("2d")).Doughnut(doughnutData);

var doughnutData = [
    {
        value: 70,
        color: "#68dff0"
    },
    {
        value: 30,
        color: "#fdfdfd"
    }
];
var myDoughnut = new Chart(document.getElementById("serverstatus01").getContext("2d")).Doughnut(doughnutData);


</script>
@stop