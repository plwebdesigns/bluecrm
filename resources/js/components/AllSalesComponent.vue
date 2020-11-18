<template>
  <div class="pt-3">
    <h4 class="font-fredricka text-center">All Sales</h4>
    <div class="container justify-content-center">
      <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
          <search-component class="mb-5 mt-3" v-if="user.isAdmin" v-on:search="getSales($event)"></search-component>
        </div>
      </div>
    </div>
    <div class="container justify-content-center">
      <div class="row">
        <div class="col">
          <table class="table table-sm table-hover">
            <thead>
              <tr class="bg-blue-realty">
                <th>CLOSING DATE</th>
                <th>AGENT NAME</th>
                <th>CLIENT NAME</th>
                <th>SALE PRICE</th>
                <th>TYPE</th>
                <th>COMMISSION</th>
                <th>LENDER</th>
                <th>TITLE CHOICE</th>
              </tr>
            </thead>
            <tbody>
                <div style="font-size: 18px" v-if="sales.length < 1">
                    No results found.
                </div>
              <tr v-for="sale in sales" :key="sale.id" v-on:click="show(sale)">
                <td>{{sale.closing_date}}</td>
                <td>{{sale.agent_name}}</td>
                <td>{{sale.client_name}}</td>
                <td>{{Number(sale.sale_price).toLocaleString('en-us', numberFormat)}}</td>
                <td>{{sale.type}}</td>
                <td>{{Number(sale.total_commission).toLocaleString('en-us', numberFormat)}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <detail-sale></detail-sale>
  </div>
</template>

<script>
import SearchComponent from "./SearchComponent";

export default {
  mounted() {
    this.getSales(null);
  },
  beforeCreate() {
    this.$loading(true);
  },
  components: {
    SearchComponent
  },
  data() {
    return {
      sales: [
        {
            closing_date: "",
            agent_name: "",
            client_name: "",
            sale_price: "",
            type: ""
        }
    ],
      user: {
        isAdmin: false
      },
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      }
    };
  },
  methods: {
    getSales(myArg) {
      let token = this.getCookie("token");
      let req = {};

      if (myArg == null) {
        req = {
          method: "get",
          url: "/api/all-sales",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token
          }
        };
      } else {
          this.$loading(true);
        req = {
          method: "post",
          url: "/api/sales",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token
          },
          data: {
            by: myArg.by,
            search_term: myArg.term
          }
        };
      }

      axios(req).then(resp => {
        this.sales = resp.data.sales;
        this.user.isAdmin = resp.data.req.isAdmin;
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
    show(sale) {
      this.$modal.show("detailSale", {
        data: sale
      });
    }
  },
  computed: {}
};
</script>
