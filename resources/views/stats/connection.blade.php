@extends('layouts.dashboard')
@section('header') Connection statistics @stop
@section('content')
    <div id="vue">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-borders">
                        <h3 class="box-title">Connection setup</h3>
                    </div>
                    <div class="box-body">
                        How to setup a connection: <br>
                        <ul class="list-group">
                            <li class="list-group-item">1. Install the BeerBoss-Python firmware on the Raspberry Pi</li>
                            <li class="list-group-item">2. Run the Configurator below</li>
                            <li class="list-group-item">3. Place the settings.py file in the root folder of the
                                BeerBoss-Python installation path
                            </li>
                            <li class="list-group-item">4. Run the BeerBoss.sh file using "sh BeerBoss.sh"</li>
                            <li class="list-group-item">5. The device info should show up on the right</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-borders">
                        <h3 class="box-title">Info about last connection</h3><br>
                        <small>Could take up to 30 seconds to update</small>
                        <br>
                        <p>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Operating System</td>
                                <td>@{{connInfo.os}}</td>
                            </tr>
                            <tr>
                                <td>Version</td>
                                <td>@{{connInfo.os_version}}</td>

                            </tr>
                            <tr>
                                <td>System architecture</td>
                                <td>@{{connInfo.architecture}}</td>

                            </tr>
                            <tr>
                                <td>Hostname</td>
                                <td>@{{connInfo.hostname}}</td>

                            </tr>
                            <tr>
                                <td>Ip</td>
                                <td>@{{connInfo.ip}}</td>
                            </tr>
                            <tr>
                                <td>Last update</td>
                                <td>@{{connInfo.updated_at}} (<span v-html="status"></span>)</td>
                            </tr>

                            </tbody>
                        </table>
                        </p>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header with-borders">
                        <h3 class="box-title">Configuration generator</h3>
                    </div>
                    <div class="box-body">
                        <form>
                            <h4>Pin settings</h4>
                            <div class="form-group">
                                <label for="coolerRelayPin">Cooler relay pin: </label>
                                <input type="text" id="coolerRelayPin" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="heaterRelayPin">Heater relay pin: </label>
                                <input type="text" id="heaterRelayPin" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="lcdAddress">Lcd I2C address: </label>
                                <input type="text" id="lcdAddress" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="sensorFridgePin">Fridge sensor address: </label>
                                <input type="text" id="sensorFridgeAddress" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="sensorBarrelPin">Barrel sensor address: </label>
                                <input type="text" id="sensorBarrelAddress" class="form-control">
                            </div>
                            <h4>Web settings (user has to exist)</h4>
                            <div class="form-group">
                                <label for="email">Login email: </label>
                                <input type="text" id="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Login password: </label>
                                <input type="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="webAddress">Web address (For this site: {{URL::to('/')}}) :</label>
                                <input type="text" id="webAddress" value="{{URL::to('/')}}" class="form-control">
                            </div>
                            <button onclick="download()" class="btn btn-info">Generate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        function download() {
            event.preventDefault();
            var data =
                "cooler_relayPin = " + $("#coolerRelayPin").val() +
                "\nheater_relayPin = " + $("#heaterRelayPin").val() +
                "\nlcd_i2c_addr = " + $("#lcdAddress").val() +
                "\nsensorFridgeAddress = " + $("#sensorFridgeAddress").val() +
                "\nsensorBarrelAddress = " + $("#sensorBarrelAddress").val() +
                "\nemail = '" + $("#email").val() +
                "'\npassword = '" + $("#password").val() +
                "'\nwebAddress = '" + $("#webAddress").val() + "'\n";
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data));
            element.setAttribute('download', 'settings.py');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
        }

        var app = new Vue({
            'el': '#vue',
            'data': {
                'connInfo': '',
            },
            'methods': {
                getConnInfo() {
                    this.connInfo = sidebar.connInfo;
                }
            },
            'computed': {
              status(){
                  if(this.connInfo === '') return 'No connection made yet';
                  if(moment().diff(moment(this.connInfo.updated_at), 'seconds') >= 15) return 'Offline <i class="fa fa-circle text-danger"></i>';
                  return 'Online <i class="fa fa-circle text-success"></i>';
              }
            },
            mounted() {
                this.getConnInfo();
                setInterval(function () {
                    this.getConnInfo();
                }.bind(this), 1000);
            }
        });
    </script>
@stop