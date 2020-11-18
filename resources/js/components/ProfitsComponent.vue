<template>
    <div>
        <nav-component :isAdmin="user.isAdmin"></nav-component>
        <div class="container">
            <h1 class="font-fredricka">Blue Profits By Agent</h1>
            <table class="table table-sm">
                <thead>
                <tr class="bg-blue-realty">
                    <th><button class="btn btn-link text-white">AGENT NAME</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('units')">UNITS SOLD</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('sellers')">SELLERS</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('buyers')">BUYERS</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('rentals')">RENTALS</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('agent_income')">AGENT INCOME</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits(null)">BLUE INCOME</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('trans')">TRANS FEES</button></th>
                    <th><button class="btn btn-link text-white" v-on:click="getProfits('total')">TOTAL INCOME</button></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="p in profits">
                    <td>{{p.agent_name}}</td>
                    <td>{{p.units_sold}}</td>
                    <td>{{p.sellers}}</td>
                    <td>{{p.buyers}}</td>
                    <td>{{p.rentals}}</td>
                    <td>${{p.agent_income.toLocaleString()}}</td>
                    <td>${{p.blue_income.toLocaleString()}}</td>
                    <td>${{p.transaction_fees.toLocaleString()}}</td>
                    <td>${{p.total_income.toLocaleString()}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProfitsComponent",
        props: ["isAdmin"],
        mounted(){
            this.getProfits(null);
        },
        data(){
            return {
                profits: {
                    agent_name: '',
                    units_sold: 0,
                    sellers: 0,
                    buyers: 0,
                    rentals: 0,
                    total_income: 0,
                    agent_income: 0,
                    blue_income: 0,
                    transaction_fees: 0
                },
                user: {
                    isAdmin: false
                }
            };
        },
        methods: {
            getProfits: function (option) {
                let token = this.getCookie('token');
                axios({
                   dataType: 'json',
                    method: 'GET',
                   url: '/api/profits',
                    headers: {
                       Accept: 'application/json',
                        Authorization: token
                    }
                }).then(resp => {
                    this.profits = resp.data.profits;
                    this.user.isAdmin = resp.data.req;
                    this.sortedProfits(option);
                });
            },
            getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(";");
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) === " ") {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) === 0) {
                        return "Bearer " + c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            sortedProfits: function (sortBy) {
                let profits = [];

                for (const key in this.profits){
                    const elem = this.profits[key];
                    profits.push(elem);
                }
                if (sortBy === 'rentals'){
                    profits.sort(function (a, b) {
                        return b.rentals - a.rentals;
                    });
                }
                else if (sortBy === 'units'){
                    profits.sort(function (a, b) {
                        return b.units_sold - a.units_sold;
                    });
                }
                else if (sortBy === 'sellers'){
                    profits.sort(function (a, b) {
                        return b.sellers - a.sellers;
                    });
                }
                else if (sortBy === 'buyers'){
                    profits.sort(function (a, b) {
                        return b.buyers - a.buyers;
                    });
                }
                else if (sortBy === 'agent_income'){
                    profits.sort(function (a, b) {
                        return b.agent_income - a.agent_income;
                    });
                }
                else if (sortBy === 'total'){
                    profits.sort(function (a, b) {
                        return b.total_income - a.total_income;
                    });
                }
                else if (sortBy === 'trans'){
                    profits.sort(function (a, b) {
                        return b.transaction_fees - a.transaction_fees;
                    });
                }
                else{
                    profits.sort(function (a, b) {
                        return b.blue_income - a.blue_income;
                    });
                }

                this.profits = profits;
            }
        },
        computed: {
        }
    }
</script>
