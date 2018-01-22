@extends('layouts.dashboard')
@section('header') Overview @stop
@section('content')
    <div id="vue">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-borders">
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
    var app = new Vue({
        el: '#vue',
        data: {
            'preppedData': {},
        },
        methods: {
            getData(){
                this.$http.get('{{route('sensordata.index')}}').then(response => {
                    this.prepData(response.body);
                    this.drawChart()
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
                var chart = new Chart(document.getElementById("tempChart"), {
                    type: 'line',
                    data: this.preppedData,
                    options: {
                        title: {
                            display: true,
                            text: 'World population per region (in millions)'
                        }
                    }
                });
            }
        },
        mounted: function () {
            this.getData();
            setInterval(function () {
                this.getData();
            }.bind(this), 30000);
        }
    });
</script>
@stop