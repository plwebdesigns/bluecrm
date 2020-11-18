<template>
    <div class="container-fluid p-0 bg-transparent">
        <div class="row justify-content-center">
            <div class="col-2">
                <h4 class="text-center">End of Quarter 4</h4>
                <table class="table table-sm table-borderless text-black text-center font-weight-bold text-danger">
                    <tr>
                        <td>{{days}}</td>
                        <td>{{hours}}</td>
                        <td>{{minutes}}</td>
                        <td>{{seconds}}</td>
                    </tr>
                    <tr>
                        <td>Days</td>
                        <td>Hours</td>
                        <td>Minutes</td>
                        <td>Seconds</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "TimerComponent",
        data: function () {
            return {
                interval: 0,
                startTime: '',
                endTime: '',
                days: '',
                hours: '',
                minutes: '',
                seconds: '',
            }
        },
        mounted() {
            this.startTime = new Date('Dec 05, 2019 08:00:00').getTime();
            this.endTime = new Date('Dec 31, 2019 23:59:59').getTime();
            this.timer(this.startTime, this.endTime);
            this.interval = setInterval(() => {
                this.timer(this.startTime, this.endTime);
            }, 1000);
        },
        methods: {
            timer: function (startTime, endTime) {
                //Get today's time/date

                let now = new Date().getTime();
                let distance = startTime - now;
                let passTime = endTime- now;

                if (distance < 0 && passTime < 0){
                    clearInterval(this.interval);
                    return;
                }else if (distance < 0 && passTime > 0){
                    this.calcTime(passTime);
                }else if (distance > 0 && passTime > 0){
                    this.calcTime(distance);
                }

            },
            calcTime: function(dist){
                // Time calculations for days, hours, minutes and seconds
                this.days = Math.floor(dist / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((dist % (1000 * 60)) / 1000);
            }
        }
    }
</script>

<style scoped>
.table td {
    border-top: none;
}
</style>
