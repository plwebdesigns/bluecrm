<template>
  <div class="pt-3">
    <div class="row pb-4">
      <div class="col-3">
        <SelectYearComponent v-on:production_year="getBreakdowns($event)"></SelectYearComponent>
      </div>
    </div>
    <div class="row">
      <div class="col" v-for="(qrt,index) in summary" v-bind:key="index">
        <h4 v-if="index < 4" class="font-fredricka">Quarter {{Number(index) + 1}}</h4>
        <h4 v-else-if="index === 4" class="font-fredricka">YTD</h4>
        <table class="table table-sm">
          <tr>
            <td>SALES VOLUME</td>
            <td>{{qrt.volume.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>SELLERS</td>
            <td>{{qrt.sellers}}</td>
          </tr>
          <tr>
            <td>BUYERS</td>
            <td>{{qrt.buyers}}</td>
          </tr>
          <tr>
            <td>RENTALS</td>
            <td>{{qrt.rentals.toLocaleString()}}</td>
          </tr>
          <tr>
            <td>REFERRALS</td>
            <td>{{qrt.referrals}}</td>
          </tr>
          <tr>
            <td>UNITS SOLD</td>
            <td>{{qrt.units.toLocaleString()}}</td>
          </tr>
          <tr>
            <td>TRANS FEES</td>
            <td>{{qrt.trans_fees.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>BLUE PROFIT</td>
            <td>{{qrt.blue_profit.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
          <tr>
            <td>MEMBERSHIP DUES</td>
            <td>{{qrt.membership_dues.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
          <tr style="border-top: black solid 2px; font-weight: bolder">
            <td>TOTAL PROFIT</td>
            <td>{{qrt.total_profit.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
        </table>
      </div>
  </div>
  </div>
</template>

<script>
import SelectYearComponent from "../SelectYearComponent";

export default {
  components: {
    SelectYearComponent
  },
  beforeCreate() {
    this.$loading(true);
  },
  mounted() {
    this.getBreakdowns(this.production_year);
  },
  data() {
    return {
      summary: [
        {
          volume: 0,
          sellers: 0,
          buyers: 0,
          units: 0,
          rentals: 0,
          referrals: 0,
          trans_fees: 0,
          blue_profit: 0,
          membership_dues: 0,
          total_profit: 0
        }
      ],
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      },
      production_year: new Date().getFullYear()
    };
  },
  methods: {
    getBreakdowns: function(prod_year) {
      let token = this.getCookie("token");
      let req = {
        method: "GET",
        url: "/api/admin",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        params: {
          production_year: prod_year
        }
      };

      axios(req).then(resp => {
        this.summary = resp.data.summary;
        this.$loading(false);
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
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }
  }
};
</script>

<style scoped>
</style>
