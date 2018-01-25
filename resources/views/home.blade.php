@extends('layouts.dashboard')
@section('header') Overview @stop
@section('content')
    <div id="vue">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Temperatuur grafiek</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="tempChart" width="800" height="450"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script>
    /*function wait(ms){
        var start = new Date().getTime();
        var end = start;
        while(end < start + ms) {
            end = new Date().getTime();
        }
    }
    var app = new Vue({
        el: '#vue',
        data: {
            'preppedData': null,
            'chart': null,

        },
        methods: {
            getData(){
                this.$http.get('{{route('sensordata.index')}}').then(response => {
                    this.prepData(response.body);
                });
            },
            prepData(data){
                this.preppedData.labels = data.map(function(x){return moment(x.recordStamp).format('ddd, hh:mm:ss')});
                this.preppedData.datasets = [{
                    data: data.map(function(x){return x.fridgeTemp}),
                    label: "Fridge temperature",
                    borderColor: "#3e95cd",
                    fill: false,
                },{
                    data: data.map(function(x){return x.barrelTemp}),
                    label: "Barrel temperature",
                    borderColor: "#8e5ea2",
                    fill: false,
                }];
            },
            drawChart(){
                this.chart = new Chart(document.getElementById("tempChart"), {
                    type: 'line',
                    data: null,
                    options: {
                        title: {
                            display: true,
                            text: 'World population per region (in millions)'
                        }
                    }
                });
            },
            updateData(){
                this.chart.data = this.preppedData;
                this.chart.update(0);
            }
        },
        mounted: function () {
            this.getData();
            this.drawChart();
        }
    });*/
</script>
@stop