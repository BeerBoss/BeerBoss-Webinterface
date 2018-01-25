@extends('layouts.dashboard')
@section('header') Manage profiles @stop
@section('content')
    <div id="vue">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create new or edit profile</h3><span style="float: right;"><div class="form-inline"><input type="text" class="form-control" v-model="name" placeholder="Name"> <button v-on:click="saveProfile" class="btn btn-success">Save profile</button></div></span>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Amount of days</th>
                                    <th>Desired temperature</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(prof, index) in profile">
                                    <td><input type="number" min="1" v-model="prof.amountDays" class="form-control"></td>
                                    <td><input type="number" step=".2" min="5" max="25" v-model="prof.desiredTemp" class="form-control"></td>
                                    <td><a v-on:click="removeFromProfile(index)"><i class="fa fa-trash"></i></a></td>
                                </tr>
                                <tr>
                                    <td><input type="number" min="1" v-model="amountDays" class="form-control"></td>
                                    <td><input type="number" step=".2" min="5" max="25" v-model="desiredTemperature" class="form-control"></td>
                                    <td><button v-on:click="addToProfile" class="btn btn-default">Add to profile</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Saved profiles</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Profile name</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>(De)Activate</th>
                                    <th>Activated on</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="savedProfile in profilesList">
                                    <td>@{{ savedProfile.name }}</td>
                                    <td><a v-on:click="editProfile(savedProfile)"><i class="fa fa-edit"></i></a></td>
                                    <td><a v-on:click="deleteProfile(savedProfile.id)"><i class="fa fa-trash"></i></a></td>
                                    <td><a v-on:click="toggleProfile(savedProfile.id)" href="#"><span v-if="savedProfile.dateStarted">Deactivate</span><span v-else>Activate</span></a></td>
                                    <td>@{{ savedProfile.dateStarted }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Profile on graph</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="profileGraph" width="500px" height="400px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Profile on graph (Formatted to dates)</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="profileGraphDates" width="500px" height="400px"></canvas>
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
                'amountDays': 1,
                'desiredTemperature': 18.0,
                'name': '',
                'profile': [],
                'profilesList': [],
                'chart': null,
                'chartDates': null,
            },
            'methods': {
                addToProfile(){
                    this.profile.push({amountDays: +this.amountDays, desiredTemp: +this.desiredTemperature});
                    this.updateCharts();
                },
                removeFromProfile(profile){
                    this.profile.splice(profile, 1);
                    this.updateCharts();
                },
                updateCharts(){
                    this.chart.data = this.graphData;
                    this.chartDates.data = this.graphDataDates;
                    this.chart.update(0);
                    this.chartDates.update(0);
                },
                getProfiles(){
                    this.$http.get('{{route('getProfiles')}}').then(response =>{
                        this.profilesList = response.body;
                    })
                },
                saveProfile(){
                    this.$http.post('{{route('saveProfile')}}', {'name': this.name, 'profiles': this.profile}, {headers: {'X-CSRF-Token': '{{csrf_token()}}'}}).then(response =>{
                        this.getProfiles();
                        this.profile = [];
                        this.updateCharts();
                    });
                },
                deleteProfile(profile){
                    this.$http.delete('{{route('deleteProfile', '')}}/'+profile, {headers: {'X-CSRF-Token': '{{csrf_token()}}'}}).then(response =>{
                        this.getProfiles();
                    });
                },
                editProfile(profile){
                    this.profile = profile.beer_profile_data;
                    this.name = profile.name;
                    this.updateCharts();
                },
                toggleProfile(profile){
                    var url = '{{route('toggleProfile', 'id')}}';
                    this.$http.get(url.replace('id', profile)).then(response =>{
                        console.log(response);
                        this.getProfiles();
                    });
                },
                drawCharts(){
                    this.chart = new Chart(document.getElementById("profileGraph"), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                data: null,
                                label: 'Desired Temperature',
                                borderColor: "#8e5ea2",
                                fill: false,
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Desired temperature over time'
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
                                        labelString: 'Time in days',
                                    }
                                }]
                            }
                        }
                    });
                    this.chartDates = new Chart(document.getElementById("profileGraphDates"), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                data: null,
                                label: 'Desired Temperature',
                                borderColor: "#8e5ea2",
                                fill: false,
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Desired temperature over time'
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
                                        labelString: 'Date',
                                    }
                                }]
                            }
                        }
                    });
                }
            },
            'computed': {
                graphData(){
                    var dayCount = 0;
                    var labels = [];
                    var dataset = [];
                    for (i= 0; i < this.profile.length; i++) {
                         for(z=0; z < this.profile[i].amountDays; z++){
                             dayCount++;
                             labels.push(dayCount);
                             dataset.push(this.profile[i].desiredTemp);
                         }
                    }
                    var data = {
                        labels: labels,
                        datasets: [{
                            data: dataset,
                            label: 'Temperature',
                            borderColor: "#8e5ea2",
                            fill: false,
                            steppedLine: 'after',
                        }]
                    };
                    return data;
                },
                graphDataDates(){
                    var dayCount = 0;
                    var labels = [];
                    var dataset = [];
                    for (i= 0; i < this.profile.length; i++) {
                        for(z=0; z < this.profile[i].amountDays; z++){
                            dayCount++;
                            labels.push(moment().add(dayCount, 'days').format('MMM Do'));
                            dataset.push(this.profile[i].desiredTemp);
                        }
                    }
                    var data = {
                        labels: labels,
                        datasets: [{
                            data: dataset,
                            label: 'Temperature',
                            borderColor: "#8e5ea2",
                            fill: false,
                            steppedLine: 'after',
                        }]
                    };
                    return data;
                }
            },
            mounted(){
                this.getProfiles();
                this.drawCharts();
            }

        });
    </script>
@stop