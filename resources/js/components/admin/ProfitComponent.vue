<template>
  <div class="container-fluid pt-3">
    <div class="row">
      <div class="col-3">
        <SelectYearComponent v-on:production_year="getProfits(null, $event)"></SelectYearComponent>
      </div>
    </div>

    <h4 class="font-fredricka">Profit Per Agent</h4>
    <table class="table table-sm">
      <thead>
        <tr class="bg-blue-realty">
          <th>
            <button class="btn btn-link text-white p-0">RANK</button>
          </th>
          <th>
            <button class="btn btn-link text-white p-0">AGENT NAME</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('units',production_year)"
            >UNITS</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('sellers',production_year)"
            >SELLERS</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('buyers',production_year)"
            >BUYERS</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('rentals',production_year)"
            >RENTALS</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('referrals',production_year)"
            >REFERRALS</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('agent_income',production_year)"
            >AGENT INCOME</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('blue',production_year)"
            >BLUE INCOME</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('trans',production_year)"
            >TRANS FEES</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('membership', production_year)"
            >MEMBERSHIP DUES</button>
          </th>
          <th>
            <button
              class="btn btn-link text-white p-0"
              v-on:click="getProfits('total', production_year)"
            >TOTAL INCOME</button>
          </th>
            <th>
                <button
                    class="btn btn-link text-white p-0"
                    v-on:click="getProfits('sales', production_year)"
                >TOTAL SALES</button>
            </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(p, index) in formatProfits" :key="index">
          <td>{{index + 1}}</td>
          <td>{{p.agent_name}}</td>
          <td>{{p.units_sold}}</td>
          <td>{{p.sellers}}</td>
          <td>{{p.buyers}}</td>
          <td>{{p.rentals}}</td>
          <td>{{p.referrals}}</td>
          <td>{{p.agent_income}}</td>
          <td>{{p.blue_income}}</td>
          <td>{{p.transaction_fees}}</td>
          <td>{{p.membership_dues}}</td>
          <td>{{p.total_income}}</td>
          <td>{{p.total_sales}}</td>
        </tr>
        <tr style="font-weight: bolder; border-top: 2px solid black">
          <td>TOTALS:</td>
          <td></td>
          <td>{{totals.t_units}}</td>
          <td>{{totals.t_sellers}}</td>
          <td>{{totals.t_buyers}}</td>
          <td>{{totals.t_rentals}}</td>
          <td>{{totals.t_referrals}}</td>
          <td>{{Number(totals.t_agent_income).toLocaleString('en-us', numberFormat)}}</td>
          <td>{{Number(totals.t_blue).toLocaleString('en-us', numberFormat)}}</td>
          <td>{{Number(totals.t_trans).toLocaleString('en-us', numberFormat)}}</td>
          <td>{{Number(totals.t_membership).toLocaleString('en-us', numberFormat)}}</td>
          <td>{{Number(totals.t_total_income).toLocaleString('en-us', numberFormat)}}</td>
          <td>{{Number(totals.t_sales).toLocaleString('en-us', numberFormat)}}</td>
        </tr>
      </tbody>
    </table>
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
  props: ["isAdmin"],
  mounted() {
    this.getProfits(null, this.production_year);
  },
  data() {
    return {
      profits: {
        agent_name: "",
        units_sold: 0,
        sellers: 0,
        buyers: 0,
        rentals: 0,
        referrals: 0,
        total_income: 0,
        agent_income: 0,
        blue_income: 0,
        transaction_fees: 0,
        membership_dues: 0,
        total_sales: 0,
      },
      user: {
        isAdmin: false
      },
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      },
      totals: {
        t_units: 0,
        t_rentals: 0,
        t_sellers: 0,
        t_buyers: 0,
        t_total_income: 0,
        t_agent_income: 0,
        t_blue: 0,
        t_trans: 0,
        t_membership: 0,
        t_sales: 0
      },
      production_year: new Date().getFullYear()
    };
  },
  methods: {
    getProfits: function(option, prod_year) {
      let token = this.getCookie("token");
      axios({
        dataType: "json",
        method: "GET",
        url: "/api/profits",
        headers: {
          Accept: "application/json",
          Authorization: token
        },
        params: {
          production_year: prod_year
        }
      }).then(resp => {
        this.profits = resp.data.profits;
        this.user.isAdmin = resp.data.req;
        this.sortedProfits(option);
        this.calcTotals();
        this.$loading(false);
      });
      this.production_year = prod_year;
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
    sortedProfits: function(sortBy) {
      let profits = [];

      for (const key in this.profits) {
        const elem = this.profits[key];
        profits.push(elem);
      }
      if (sortBy === "rentals") {
        profits.sort(function(a, b) {
          return b.rentals - a.rentals;
        });
      } else if (sortBy === "units") {
        profits.sort(function(a, b) {
          return b.units_sold - a.units_sold;
        });
      } else if (sortBy === "sellers") {
        profits.sort(function(a, b) {
          return b.sellers - a.sellers;
        });
      } else if (sortBy === "buyers") {
        profits.sort(function(a, b) {
          return b.buyers - a.buyers;
        });
      } else if (sortBy === "referrals") {
        profits.sort(function(a, b) {
          return b.referrals - a.referrals;
        });
      } else if (sortBy === "agent_income") {
        profits.sort(function(a, b) {
          return b.agent_income - a.agent_income;
        });
      } else if (sortBy === "blue") {
        profits.sort(function(a, b) {
          return b.blue_income - a.blue_income;
        });
      } else if (sortBy === "trans") {
        profits.sort(function(a, b) {
          return b.transaction_fees - a.transaction_fees;
        });
      } else if (sortBy === "sales"){
          profits.sort(function(a, b) {
              return b.total_sales - a.total_sales;
          });
      } else if (sortBy === "membership"){
        profits.sort(function(a, b) {
          return b.mebership_dues - a.membership_dues;
        });
      }
      else {
        profits.sort(function(a, b) {
          return b.total_income - a.total_income;
        });
      }

      this.profits = profits;
    },
    calcTotals: function() {
      let obj = {
        t_units: 0,
        t_rentals: 0,
        t_sellers: 0,
        t_buyers: 0,
        t_referrals: 0,
        t_total_income: 0,
        t_agent_income: 0,
        t_blue: 0,
        t_trans: 0,
        t_membership: 0,
        t_sales: 0
      };
      for (const n in this.profits) {
        const e = this.profits[n];
        obj.t_units += e.units_sold;
        obj.t_rentals += e.rentals;
        obj.t_referrals += e.referrals;
        obj.t_buyers += e.buyers;
        obj.t_sellers += e.sellers;
        obj.t_total_income += e.total_income;
        obj.t_agent_income += e.agent_income;
        obj.t_blue += e.blue_income;
        obj.t_trans += e.transaction_fees;
        obj.t_membership += e.membership_dues;
        obj.t_sales += e.total_sales;
      }

      this.totals = obj;
    }
  },

  computed: {
    formatProfits: function() {
      let p = this.profits;

      for (const key in p) {
        for (const i in p[key]) {
          if (
            i === "agent_income" ||
            i === "blue_income" ||
            i === "total_income" ||
            i === "transaction_fees" ||
              i === "total_sales"
          ) {
            p[key][i] = p[key][i].toLocaleString("en-US", this.numberFormat);
          }
        }
      }

      return p;
    }
  }
};
</script>
<style scoped>
.btn-link {
  font-size: 13px;
}
</style>
