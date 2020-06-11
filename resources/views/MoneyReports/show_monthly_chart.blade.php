@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
    <h4> نمودار فروش ماهیانه {{$namad->name}}</h4>
    </div>
    <div class="col-md-12 ">


        <div id="chart"></div>




    </div>


</div>


</div>
@endsection




@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function(){
    
        years = <?php echo json_encode($years) ?>;
        values = <?php echo json_encode($array_values) ?>;
      if( years.length === 1){
       series = [{
          name: years[0],
          data: values[1]
        }]
      }else{
        series= [{
          name: years[0],
          data:values[1]
        }, {
          name: years[1],
          data: values[2]
        }]
      }
        var options = {
            series:series,
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories:<?php echo json_encode($months_label) ?>,
        },
        yaxis: {
          title: {
            text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " ریال"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      
      
        })
</script>
@endsection