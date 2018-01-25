@extends('layouts.dashboard')
@section('header') Temperature statistics @stop
@section('content')
    <div id="vue">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Current temperatures</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="description-block border-right">
                                    <span v-html="fridgePercOffHtml"></span>
                                    <h1>@{{ lastTemp.fridgeTemp }}</h1>
                                    <span class="description-text">Fridge temperature</span>
                                </div>
                            </div>
                            <div class="col-log-6">
                                <div class="description-block">
                                    <span v-html="barrelPercOffHtml"></span>
                                    <h1>@{{ lastTemp.barrelTemp }}</h1>
                                    <span class="description-text">Barrel temperature</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="description-block border-right">
                                    <h1>@{{ averageFridgeTemp }}</h1>
                                    <span class="description-text">Average Fridge temperature today</span>
                                </div>
                            </div>
                            <div class="col-log-6">
                                <div class="description-block">
                                    <h1>@{{ averageBarrelTemp }}</h1>
                                    <span class="description-text">Average Barrel temperature today</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="description-block border-right">
                                    <h1>@{{ maxFridgeTemp }}</h1>
                                    <span class="description-text">Max Fridge temperature today</span>
                                </div>
                            </div>
                            <div class="col-log-6">
                                <div class="description-block">
                                    <h1>@{{ maxBarrelTemp }}</h1>
                                    <span class="description-text">Max Barrel temperature today</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="description-block border-right">
                                    <h1>@{{ minFridgeTemp }}</h1>
                                    <span class="description-text">Min Fridge temperature today</span>
                                </div>
                            </div>
                            <div class="col-log-6">
                                <div class="description-block">
                                    <h1>@{{ minBarrelTemp }}</h1>
                                    <span class="description-text">Min Barrel temperature today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Live temperature graph</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="temperatureGraph" width="500px" height="400px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daily temperature graph</h3>
                    </div>
                    <div class="box-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        var app = new Vue({
            'el': '#vue',
            'data': {
                'chart': null,
                'lastTemp': '',
                'dailyTemps': [],
                'activeProfile': '',
                'barrelPercOff': '',
                'fridgePercOff': '',
            },
            'methods': {
                updateCharts(){
                    if(sidebar.online){
                        this.chart.data.labels.push(moment().format('hh:mm:ss'));
                        this.chart.data.datasets[0].data.push(this.lastTemp.fridgeTemp);
                        this.chart.data.datasets[1].data.push(this.lastTemp.barrelTemp);
                        this.chart.data.datasets[2].data.push(sidebar.activeProfilePart.desiredTemp);
                        this.chart.update();
                    }
                },
                getLastTemp(){
                    this.lastTemp = sidebar.lastTemp;
                },
                getDailyTemps(){
                    this.$http.get('{{route('getDailyTemps')}}').then(response => {
                        this.dailyTemps = response.body;
                    })
                },
                getActiveProfile(){
                    this.activeProfile = sidebar.activeProfile;
                },
                getPercentages(){
                    var desiredTemp = +sidebar.activeProfilePart.desiredTemp;
                    var temp = +this.lastTemp.barrelTemp;
                    this.barrelPercOff = Math.round(((temp-desiredTemp)/((temp+desiredTemp)/2))*10000)/100;
                    temp = +this.lastTemp.fridgeTemp;
                    this.fridgePercOff = Math.round(((temp-desiredTemp)/((temp+desiredTemp)/2))*10000)/100;
                },
                drawCharts(){
                    this.chart = new Chart(document.getElementById("temperatureGraph"), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                data: [],
                                label: 'Fridge temperature',
                                borderColor: "#4842f4",
                                fill: false,
                            }, {
                                data: [],
                                label: 'Barrel temperature',
                                borderColor: "#8e5ea2",
                                fill: false,
                            }, {
                                data: [],
                                label: 'Desired temperature',
                                borderColor: "#f142f4",
                                fill: false,
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Temperature graph'
                            },
                            scales: {
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Temperature'
                                    },
                                    ticks: {
                                        stepSize: 2,
                                        beginAtZero:true,
                                        suggestedMax: 25,
                                        suggestedMin: 10,
                                    }
                                }],
                                xAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time in seconds',
                                    }
                                }]
                            }
                        }
                    });
                }
            },
            'computed': {
                barrelPercOffHtml(){
                    if(+this.barrelPercOff < 0){
                        return '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i>' + this.barrelPercOff + '% Below desired temperature</span>'
                    }
                    return '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> ' + this.barrelPercOff + '% Above desired temperature</span>'
                },
                fridgePercOffHtml(){
                    if(+this.fridgePercOff < 0){
                        return '<span class="description-percentage text-red"><i class="fa fa-caret-down"></i>' + this.fridgePercOff + '% Below desired temperature</span>'
                    }
                    return '<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> ' + this.fridgePercOff + '% Above desired temperature</span>'
                },
                averageFridgeTemp(){
                    var count = 0;
                    this.dailyTemps.map(function(x){return count += +x.fridgeTemp});
                    return Math.round((count/this.dailyTemps.length)*100)/100;
                },
                averageBarrelTemp(){
                    var count = 0;
                    this.dailyTemps.map(function(x){return count += +x.barrelTemp});
                    return Math.round((count/this.dailyTemps.length)*100)/100;
                },
                maxFridgeTemp(){
                    var biggest = 0;
                    this.dailyTemps.map(function(x){biggest = Math.max(biggest, +x.fridgeTemp)});
                    return biggest;
                },
                maxBarrelTemp(){
                    var biggest = 0;
                    this.dailyTemps.map(function(x){biggest = Math.max(biggest, +x.barrelTemp)});
                    return biggest;
                },
                minFridgeTemp(){
                    var smallest = 999;
                    this.dailyTemps.map(function(x){smallest = Math.min(smallest, +x.fridgeTemp)});
                    return smallest;
                },
                minBarrelTemp(){
                    var smallest = 999;
                    this.dailyTemps.map(function(x){smallest = Math.min(smallest, +x.barrelTemp)});
                    return smallest;
                }
            },
            mounted(){
                this.drawCharts();
                this.getLastTemp();
                this.getActiveProfile();
                this.getDailyTemps();
                setInterval(function () {
                    this.getLastTemp();
                    this.getActiveProfile();
                    this.updateCharts();
                    this.getPercentages();
                    this.getDailyTemps();
                }.bind(this), 5000);
            }

        });
    </script>
@stop