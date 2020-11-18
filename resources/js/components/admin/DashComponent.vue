<template>
  <div class="pt-3">
    <div class="row pb-4">
      <div class="col-2">
        <SelectYearComponent v-on:production_year="getBreakdowns($event)"></SelectYearComponent>
      </div>
    </div>
    <div class="row">
      <div class="col" v-for="(qrt,index) in summary" v-bind:key="index">
        <h4 class="font-fredricka">Quarter {{Number(index) + 1}}</h4>
        <table class="table table-sm">
          <tr>
            <td>SALES VOLUME</td>
            <td>{{qrt.total_sales_volume.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>SELLERS</td>
            <td>{{qrt.total_sellers}}</td>
          </tr>
          <tr>
            <td>BUYERS</td>
            <td>{{qrt.total_buyers}}</td>
          </tr>
          <tr>
            <td>RENTALS</td>
            <td>{{qrt.total_rentals.toLocaleString()}}</td>
          </tr>
          <tr>
            <td>REFERRALS</td>
            <td>{{qrt.total_referrals}}</td>
          </tr>
          <tr>
            <td>UNITS SOLD</td>
            <td>{{qrt.total_units_sold.toLocaleString()}}</td>
          </tr>
          <tr>
            <td>TRANS FEES</td>
            <td>{{qrt.total_trans_fees.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>BLUE PROFIT</td>
            <td>{{qrt.total_blue_profit.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
          <tr style="border-top: black solid 2px; font-weight: bolder">
            <td>TOTAL PROFIT</td>
            <td>{{qrt.total_profit.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
        </table>
      </div>
      <div class="col">
        <h4 class="font-fredricka">YTD Totals</h4>
        <table class="table table-sm">
          <tr>
            <td>SALES VOLUME</td>
            <td>{{ytd.total_sales_volume.toLocaleString('en-US', numberFormat)}}</td>
          </tr>
          <tr>
            <td>SELLERS</td>
            <td>{{ytd.total_sellers}}</td>
          </tr>
          <tr>
            <td>BUYERS</td>
            <td>{{ytd.total_buyers}}</td>
          </tr>
          <tr>
            <td>RENTALS</td>
            <td>{{ytd.total_rentals}}</td>
          </tr>
          <tr>
            <td>REFERRALS</td>
            <td>{{ytd.total_referrals}}</td>
          </tr>
          <tr>
            <td>UNITS SOLD</td>
            <td>{{ytd.total_units_sold}}</td>
          </tr>
          <tr>
            <td>TRANS FEES</td>
            <td>{{Number(ytd.total_trans_fees).toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>BLUE PROFIT</td>
            <td>{{Number(ytd.total_blue_profit).toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr style="border-top: black solid 2px; font-weight: bolder">
            <td>TOTAL PROFIT</td>
            <td>{{Number(ytd.total_trans_fees + ytd.total_blue_profit).toLocaleString('en-US', numberFormat)}}</td>
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
          total_sales_volume: 0,
          total_sellers: 0,
          total_buyers: 0,
          total_units_sold: 0,
          total_rentals: 0,
          total_referrals: 0,
          total_trans_fees: 0,
          total_blue_profit: 0,
          total_profit: 0
        }
      ],
      ytd: {
        total_sales_volume: 0,
        total_units_sold: 0,
        total_buyers: 0,
        total_sellers: 0,
        total_rentals: 0,
        total_referrals: 0,
        total_trans_fees: 0,
        total_blue_profit: 0,
        total_profit: 0
      },
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
        this.getTotals();
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
    },
    getTotals: function() {
      let total_blue_profit = 0;
      let total_sales_volume = 0;
      let total_sellers = 0;
      let total_buyers = 0;
      let total_referrals = 0;
      let total_units_sold = 0;
      let total_rentals = 0;
      let total_trans = 0;

      this.summary.forEach(function(obj) {
        total_sales_volume += obj.total_sales_volume;
        total_blue_profit += obj.total_blue_profit;
        total_rentals += obj.total_rentals;
        total_referrals += obj.total_referrals;
        total_units_sold += obj.total_units_sold;
        total_trans += obj.total_trans_fees;
        total_buyers += obj.total_buyers;
        total_sellers += obj.total_sellers;
      });
      this.ytd.total_blue_profit = total_blue_profit;
      this.ytd.total_sales_volume = total_sales_volume;
      this.ytd.total_buyers = total_buyers;
      this.ytd.total_sellers = total_sellers;
      this.ytd.total_referrals = total_referrals;
      this.ytd.total_trans_fees = total_trans;
      this.ytd.total_units_sold = total_units_sold;
      this.ytd.total_rentals = total_rentals;
    }
  }
};
</script>

<style scoped>
</style>
