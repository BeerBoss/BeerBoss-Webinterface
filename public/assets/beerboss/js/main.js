function setNavigation() {
    var path = window.location.href;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    $(".sidebar-menu a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).closest('li').addClass('active');
        }
    });
}
var sidebar = new Vue({
    'el': '#vue-sidebar',
    'data': {
        'connInfo': '',
        'activeProfile': null,
        'lastTemp': '',
    },
    'methods': {
        getConnInfo() {
            this.$http.get(connInfoUrl).then(response => {
                this.connInfo = response.body;
            })
        },
        getActiveProfile(){
            this.$http.get(activeProfileUrl).then(response => {
                this.activeProfile = response.body;
            })
        },
        getLastTemp(){
            this.$http.get(lastTempUrl).then(response => {
                this.lastTemp = response.body;
            });
        },
    },
    'computed': {
        status(){
            if(this.connInfo === '') return 'No connection made yet';
            if(moment().diff(moment(this.connInfo.updated_at), 'seconds') >= 15) return 'Offline <i class="fa fa-circle text-danger"></i>';
            return 'Online <i class="fa fa-circle text-success"></i>';
        },
        online(){
            return this.connInfo !== '' && moment().diff(moment(this.connInfo.updated_at), 'seconds') <= 15;
        },
        activeProfilePart(){

            if(this.activeProfile){
                var searchDay = 0;
                var currentDay = this.activeProfileDay;
                for(i=0; i < this.activeProfile.beer_profile_data.length; i++){
                    searchDay += +this.activeProfile.beer_profile_data[i].amountDays;
                    if(currentDay <= searchDay){
                        return this.activeProfile.beer_profile_data[i];
                    }
                }
            }

        },
        activeProfileDay(){
            return moment().diff(this.activeProfile.dateStarted, 'days')+1;
        }
    },
    mounted(){
        setNavigation();
        this.getActiveProfile();
        this.getConnInfo();
        this.getLastTemp();
        setInterval(function () {
            this.getActiveProfile();
            this.getLastTemp();
            this.getConnInfo();
        }.bind(this), 5000);
    }
});