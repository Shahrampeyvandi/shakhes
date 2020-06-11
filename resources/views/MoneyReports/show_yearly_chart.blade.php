@extends('layout.temp')
@section('content')
<style>
    td input {
        width: 50px !important;
    }
</style>
<div class="container-fluid panel-table mt-5">
    <div class="head-panel">
    <h4> نمودار درآمد و سود سالیانه {{$namad->name}}</h4>
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
        array =<?php echo json_encode($array) ?>;
        keys=[];
        profits =[];
        loss=[];
       
        Object.keys(array).map( (each)=> {
            keys.push(each)
            profits.push(array[each]['profit'])
            loss.push(array[each]['loss'])
        })
      
       series= [{
          name: 'درآمد',
          data:profits
        }, {
          name:'سود',
          data: loss
        }]
      
        var options = {
            series:series,
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '35%',
            endingShape: 'flat'
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
          categories:keys,
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