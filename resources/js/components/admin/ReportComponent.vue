<template>
  <div class="container increased-width">
    <div class="row pt-4 pl-3">
      <h5>Choose an Agent</h5>
    </div>
    <div class="row pb-2 pl-3">
      <div class="col-2">
        <form>
          <select
            class="custom-select"
            v-on:change="getReport(production_year); backToDefault()"
            v-model="options.agent_name"
          >
            <option>--Select One--</option>
            <option v-for="(agent, index) in all_agents" :key="index">{{agent}}</option>
          </select>
        </form>
      </div>
      <div class="col-3" v-if="options.agent_name !== ''">
        <select-year-component v-on:production_year="getReport($event)"></select-year-component>
      </div>
      <div class="col-2">
        <button
          id="pdfbtn"
          type="button"
          class="btn btn-secondary btn-sm"
          @click="generatePDF()"
          hidden
        >Print</button>
      </div>
    </div>
    <div class="row" v-if="options.agent_name !== ''">
        <div class="col-3">
            <profile-component :curAgent="agent" v-on:uploadComplete="getReport(production_year)"></profile-component>
        </div>
      <div class="col-7">
        <div class="row">
            <table id="reportTable" class="table table-sm table-hover text-nowrap">
                <thead>
                <tr class="bg-blue-realty">
                    <th>#</th>
                    <th>TYPE</th>
                    <th>CLIENT</th>
                    <th>ADDRESS</th>
                    <th>SALE PRICE</th>
                    <th>TOTAL COMMISSION</th>
                    <th>AGENT COMMISSION</th>
                    <th>SPLIT</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(sale, index) in agent_sales" :key="index" v-on:click="show(sale)">
                    <td>{{index +1}}</td>
                    <td>{{sale.type}}</td>
                    <td>{{sale.client_name}}</td>
                    <td>{{sale.address}}</td>
                    <td>{{sale.sale_price}}</td>
                    <td>{{sale.total_commission}}</td>
                    <td>{{sale.commission}}</td>
                    <td>{{sale.split}}%</td>
                </tr>
                <tr style="border-top: 2px solid black; font-weight: bolder">
                    <td>TOTALS</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{totals.total_sales.toLocaleString('en-US', numberFormat)}}</td>
                    <td>{{totals.total_commission.toLocaleString('en-US', numberFormat)}}</td>
                    <td>{{totals.total_agent_commission.toLocaleString('en-US', numberFormat)}}</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
          <div class="row mt-4">
              <h6>Split Sales</h6>
              <table class="table table-sm table-hover text-nowrap">
                  <thead>
                  <tr class="bg-blue-realty">
                      <th>#</th>
                      <th>TYPE</th>
                      <th>CLIENT</th>
                      <th>ADDRESS</th>
                      <th>SALE PRICE</th>
                      <th>TOTAL COMMISSION</th>
                      <th>AGENT COMMISSION</th>
                      <th>SPLIT</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(sale, index) in split_sales" :key="index" v-on:click="show(sale)">
                      <td>{{index +1}}</td>
                      <td>{{sale.type}}</td>
                      <td>{{sale.client_name}}</td>
                      <td>{{sale.address}}</td>
                      <td>{{sale.sale_price}}</td>
                      <td>{{sale.total_commission}}</td>
                      <td>{{sale.commission}}</td>
                      <td>{{sale.split}}%</td>
                  </tr>
                  </tbody>
              </table>
          </div>
      </div>
      <detail-sale></detail-sale>
    </div>
  </div>
</template>

<script>
import SelectYearComponent from "../SelectYearComponent";
import ProfileComponent from "./ProfileComponent";

export default {
  components: {
    SelectYearComponent,
      ProfileComponent
  },
  mounted() {
    this.getAllDropdowns();
  },
    props: {
      curAgent: Object
    },
  data() {
    return {
      all_agents: [],
        agent: {

        },
      agent_sales: {
        id: 0,
        type: "",
        client_name: "",
        address: "",
        sale_price: 0,
        total_commission: 0,
        commission: 0,
        split: 0
      },
        split_sales: {

        },
      totals: {
        total_agent_commission: 0,
        total_commission: 0,
        total_sales: 0
      },
      options: {
        agent_name: ""
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
    getAllDropdowns() {
      const req = {
        method: "GET",
        url: "/api/allDropdowns"
      };

      $.ajax({
        type: "get",
        url: "/api/allDropdowns"
      }).done(resp => {
        this.all_agents = resp.agents;
      });
    },
    getReport(prod_year) {
      let token = this.getCookie("token");

      $.ajax({
        type: "POST",
        url: "/api/report",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: {
          options: {
            agent_name: this.options.agent_name
          },
          production_year: prod_year
        }
      }).done(resp => {
        this.agent_sales = resp.agent_sales;
        this.split_sales = resp.split_sales;
        this.totals = resp.totals;
        this.agent = resp.agent;
      });

      this.$emit("agentChanged");
      $("#pdfbtn").attr("hidden", false);
    },
    show(sale) {
      this.$modal.show("detailSale", {
        data: sale
      });
    },
    backToDefault() {
      this.production_year = "2020";
      $("#production_year option").prop("selected", function() {
        return this.defaultSelected;
      });
    },
    generatePDF() {
      window.open(
        "/generate_pdf/" + this.options.agent_name + "/" + this.production_year,
        "_blank"
      );
    }
  },
  computed: {}
};
</script>

<style scoped>
</style>
