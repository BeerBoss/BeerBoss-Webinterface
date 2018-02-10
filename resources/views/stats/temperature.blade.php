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
                        <div class="row" v-if="online && lastTemp">
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
                        <div class="row" v-else>
                            <div class="col-lg-12">
                                <div class="alert alert-warning">No live data available. Make a connection!</div>
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
                        <h3 class="box-title">Daily temperature graph (Does not update)</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="dailyTemperatureGraph" width="500px" height="400px"></canvas>
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
                'dailyChart': null,
                'lastTemp': '',
                'dailyTemps': [],
                'activeProfile': '',
                'barrelPercOff': '',
                'fridgePercOff': '',
            },
            'methods': {
                updateCharts(){
                    if(sidebar.online){
                        this.chart.data.labels.push(moment().format('HH:mm:ss'));
                        this.chart.data.datasets[0].data.push(this.lastTemp.fridgeTemp);
                        this.chart.data.datasets[1].data.push(this.lastTemp.barrelTemp);
                        if(sidebar.activeProfile) this.chart.data.datasets[2].data.push(sidebar.activeProfilePart.desiredTemp);
                        this.chart.update();
                    }
                },
                getLastTemp(){
                    this.lastTemp = sidebar.lastTemp;
                },
                getDailyTemps(){
                    this.$http.get('{{route('getDailyTemps')}}').then(response => {
                        this.dailyTemps = response.body;
                        this.dailyChart = new Chart(document.getElementById("dailyTemperatureGraph"), {
                            type: 'line',
                            data: {
                                labels: this.dailyTemps.map(function(x){ return moment(x.recordStamp).format('HH:mm:ss') }),
                                datasets: [{
                                    data: this.dailyTemps.map(function(x){ return x.fridgeTemp }),
                                    label: 'Fridge temperature',
                                    borderColor: "#4842f4",
                                    fill: false,
                                }, {
                                    data: this.dailyTemps.map(function(x){ return x.barrelTemp }),
                                    label: 'Barrel temperature',
                                    borderColor: "#8e5ea2",
                                    fill: false,
                                }],
                            },
                            options: {
                                title: {
                                    display: true,
                                    text: 'Daily temperature graph'
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
                                            labelString: 'Time',
                                        }
                                    }]
                                }
                            }
                        });
                    })
                },
                getActiveProfile(){
                    if(sidebar.activeProfile)
                        this.activeProfile = sidebar.activeProfile;
                },
                getPercentages(){
                    if(sidebar.activeProfile){
                        var desiredTemp = +sidebar.activeProfilePart.desiredTemp;
                        var temp = +this.lastTemp.barrelTemp;
                        this.barrelPercOff = Math.round(((temp-desiredTemp)/desiredTemp)*10000)/100;
                        temp = +this.lastTemp.fridgeTemp;
                        this.fridgePercOff = Math.round(((temp-desiredTemp)/desiredTemp)*10000)/100;
                    }

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
                                        labelString: 'Time',
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
                    var smallest = this.dailyTemps[0].fridgeTemp;
                    this.dailyTemps.map(function(x){smallest = Math.min(smallest, +x.fridgeTemp)});
                    return smallest;
                },
                minBarrelTemp(){
                    var smallest = this.dailyTemps[0].barrelTemp;
                    this.dailyTemps.map(function(x){smallest = Math.min(smallest, +x.barrelTemp)});
                    return smallest;
                },
                online(){
                    return sidebar.connInfo !== '' && moment().diff(moment(sidebar.connInfo.updated_at), 'seconds') <= 15;
                },
            },
            mounted(){
                this.getDailyTemps();
                this.getLastTemp();
                this.getActiveProfile();
                this.drawCharts();
                setInterval(function () {
                    this.getLastTemp();
                    this.getActiveProfile();
                    this.getPercentages();
                }.bind(this), 1000);
                setInterval(function() {
                    this.updateCharts();
                }.bind(this), 5000);
            }

        });
    </script>
@stop