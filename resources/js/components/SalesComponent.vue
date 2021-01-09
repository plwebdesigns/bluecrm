<template>
  <div>
    <nav-component :isAdmin="user.isAdmin"></nav-component>
    <div class="container justify-content-center increased-width">
      <div class="row">
        <profile-component></profile-component>
        <div class="col">
          <div class="row pb-2">
            <div class="col-4">
              <h5
                class="font-fredricka text-left"
              >{{user.agent_name + " " + this.production_year}} Production</h5>
            </div>
            <div class="col-5">
              <search-component v-on:search="searchProduction($event)"></search-component>
            </div>
            <div class="col-3 justify-content-end">
                <SelectYearComponent v-on:production_year="changeYear($event)"></SelectYearComponent>
            </div>
          </div>
          <table class="table table-sm table-hover">
            <thead>
              <tr class="bg-blue-realty">
                <th>CLOSING DATE</th>
                <th>CLIENT NAME</th>
                <th>SALE PRICE</th>
                <th>TYPE</th>
                <th>COMMISSION</th>
                <th>SPLIT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sale, index) in formattedSales" v-on:click="show(sale)" :key="index">
                <td>{{sale.closing_date}}</td>
                <td>{{sale.client_name}}</td>
                <td>{{sale.sale_price}}</td>
                <td>{{sale.type}}</td>
                <td>{{sale.pivot.commission}}</td>
                <td>{{sale.pivot.split}}%</td>
              </tr>
              <tr style="border-top: solid 2px black; font-weight: bolder">
                <td>TOTALS</td>
                <td></td>
                <td>{{user.ytd_sales.toLocaleString('en-US', numberFormat)}}</td>
                <td></td>
                <td>{{user.ytd_commission.toLocaleString('en-US', numberFormat)}}</td>
                <td></td>
              </tr>
            </tbody>
          </table>
          <h5 class="font-fredricka text-left">{{user.agent_name}} Split Sales</h5>
          <table class="table table-sm table-hover">
            <thead>
              <tr class="bg-blue-realty">
                <th>CLOSING DATE</th>
                <th>CLIENT NAME</th>
                <th>SALE PRICE</th>
                <th>TYPE</th>
                <th>COMMISSION</th>
                <th>SPLIT</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(team_sale, index) in formattedSplitSales"
                v-on:click="show(team_sale)"
                :key="index"
              >
                <td>{{team_sale.closing_date}}</td>
                <td>{{team_sale.client_name}}</td>
                <td>{{team_sale.sale_price}}</td>
                <td>{{team_sale.type}}</td>
                <td>{{team_sale.pivot.commission}}</td>
                <td>{{team_sale.pivot.split}}%</td>
              </tr>
              <tr style="border-top: solid 2px black; font-weight: bolder">
                <td>TOTALS</td>
                <td></td>
                <td>{{ (user.ytd_split_sales > 0) ? user.ytd_split_sales.toLocaleString('en-US', numberFormat) : " "}}</td>
                <td></td>
                <td>{{user.ytd_split_commission.toLocaleString('en-US', numberFormat)}}</td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <quarter-breakdown :production_yr="production_year" :key="production_year"></quarter-breakdown>
        </div>
      </div>
    </div>
    <admin-sale></admin-sale>
    <change-pass-component></change-pass-component>
  </div>
</template>

<script>
import ChangePass from "./ChangePassComponent";
import quarterBreakdown from "./QuarterBreakdown";
import SelectYearComponent from "./SelectYearComponent";

export default {
  components: {
    changePassComponent: ChangePass,
    quarterBreakdown: quarterBreakdown,
      SelectYearComponent: SelectYearComponent
  },
  mounted() {
    this.getSales();
  },
  props: {
    production_yr: "",
  },
  data() {
    return {
      sales: {
        closing_date: "",
        agent_name: "",
        client_name: "",
        sale_price: "",
        type: "",
        pivot: {
          commission: "",
          split: "",
        },
      },
      team_sales: {
        closing_date: "",
        agent_name: "",
        client_name: "",
        sale_price: "",
        type: "",
        pivot: {
          commission: "",
          split: "",
        },
      },
      user: {
        isAdmin: false,
        agent_name: "",
        ytd_sales: 0,
        ytd_commission: 0,
        ytd_split_commission: 0,
        ytd_split_sales: 0,
      },
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD",
      },
      production_year: new Date().getFullYear(),
    };
  },
  methods: {
    getSales() {
      let token = this.getCookie("token");

      let req = {};

      req = {
        method: "get",
        url: "/api/sales",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        },
        params: {
          production_year: this.production_year,
        },
      };

      axios(req)
        .then((resp) => {
          this.sales = resp.data.sales;
          this.user.isAdmin = resp.data.req.isAdmin;
          this.user.agent_name = resp.data.req.agent_name;
          this.user.ytd_sales = resp.data.ytd_sales;
          this.user.ytd_commission = resp.data.ytd_commission;
          this.user.ytd_split_commission = resp.data.ytd_split_commission;
          this.user.ytd_split_sales = resp.data.ytd_split_sales;
          this.team_sales = resp.data.team_sales;
        })
        .catch(function (error) {
          if (error.response.status === 401) {
            window.location = "/login";
          }
        });
    },
    getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(";");
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    },
    show(sale) {
      this.$modal.show("adminSales", {
        data: sale,
      });
    },
    changeYear(year) {
      // this.production_year = document.getElementById("production_year").value;
      let token = this.getCookie("token");

      let request = {
        method: "GET",
        url: "/api/sales",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        },
        params: {
          production_year: year
        },
      };

      axios(request).then((resp) => {
        this.sales = resp.data.sales;
        this.user.isAdmin = resp.data.req.isAdmin;
        this.user.agent_name = resp.data.req.agent_name;
        this.user.ytd_sales = resp.data.ytd_sales;
        this.user.ytd_commission = resp.data.ytd_commission;
        this.user.ytd_split_commission = resp.data.ytd_split_commission;
        this.user.ytd_split_sales = resp.data.ytd_split_sales;
        this.team_sales = resp.data.team_sales;
      });
    },
    searchProduction: function (s) {
      let search_term = s.term;
      let token = this.getCookie("token");

      $.ajax({
        type: "post",
        url: "/api/search_production",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        },
        data: {
          term: search_term,
          production_year: this.production_year,
        },
      }).done((resp) => {
        this.sales = resp.sales;
        this.team_sales = resp.split_sales;
      });
    },
  },
  computed: {
    formattedSales: function () {
      let sales = [];
      for (const salesKey in this.sales) {
        let sale = this.sales[salesKey];
        sale.closing_date = new Date(sale.closing_date).toLocaleDateString();
        sale.sale_price = Number(sale.sale_price).toLocaleString(
          "en-US",
          this.numberFormat
        );
        sale.pivot.commission = Number(sale.pivot.commission).toLocaleString(
          "en-US",
          this.numberFormat
        );
        sale.pivot.split = sale.pivot.split * 100;
        sales.push(sale);
      }
      sales.sort(function (a, b) {
        return new Date(a.closing_date) - new Date(b.closing_date);
      });

      return sales;
    },
    formattedSplitSales: function () {
      let sales = [];
      for (const salesKey in this.team_sales) {
        let sale = this.team_sales[salesKey];
        sale.closing_date = new Date(sale.closing_date).toLocaleDateString();
        sale.sale_price = Number(sale.sale_price).toLocaleString(
          "en-US",
          this.numberFormat
        );
        sale.pivot.commission = Number(sale.pivot.commission).toLocaleString(
          "en-US",
          this.numberFormat
        );
        sale.pivot.split = sale.pivot.split * 100;
        sales.push(sale);
      }

      sales.sort(function (a, b) {
        return new Date(a.closing_date) - new Date(b.closing_date);
      });

      return sales;
    },
  },
};
</script>
