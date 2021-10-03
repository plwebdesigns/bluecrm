<template>
  <div class="pt-3">
    <h4 class="font-fredricka text-center">All Sales</h4>
    <div class="container justify-content-center">
      <div class="row">
        <div class="col-2">
          <select
          id="agent_name"
          class="custom-select mb-5 mt-3"
          v-on:input="searchSales($event)"
          >
            <option value="">Select Agent</option>
            <option
                v-for="(item, index) in this.all_agents"
                :key="index"
                :value="item"
              >{{item}}</option>
          </select>

        </div>
        <div class="col-2">
          <select
          id="mortgage_choice"
          class="custom-select mb-5 mt-3"
          v-on:input="searchSales($event)"
          >
            <option value="">Select Lender</option>
            <option
                v-for="(item, index) in this.all_mortgages"
                :key="index"
                :value="item.mortgage_names"
              >{{item.mortgage_names}}</option>
          </select>
        </div>
        <div class="col-2">
            <select
            id="title_choice"
          class="custom-select mb-5 mt-3"
          v-on:input="searchSales($event)"
          >
            <option value="">Select Title Company</option>
            <option
                v-for="(item, index) in all_titles"
                :key="index"
                :value="item.title_names"
              >{{item.title_names}}</option>
          </select>
          </div>
          <div class="col-6">
              <div class="input-group mb-5 mt-3">
                  <input class="form-control mr-1" type="date" id="beginDate">
                  <span class="align-bottom"> -- </span>
                  <input class="form-control ml-1" type="date" id="endDate">
		              <button id="search" class="btn btn-outline-dark ml-1" v-on:click="searchSales($event)">Search</button>
              </div>
          </div>
      </div>
    <div class="row mb-3">
        <div class="col-3"></div>
        <div class="col">
            <search-component v-on:search="getSales($event)"></search-component>
        </div>
        <div class="col">
            <search-component2 v-on:search_clients="getSales($event)"></search-component2>
        </div>
        <div class="col-3"></div>
    </div>
    </div>
    <div class="container-fluid justify-content-center">
      <div class="row">
        <div class="col">
          <table class="table table-sm table-hover">
            <thead>
              <tr class="bg-blue-realty">
                <th>CLOSING DATE</th>
                <th>AGENT NAME</th>
                <th>CLIENT NAME</th>
                <th>ADDRESS</th>
                <th>SALE PRICE</th>
                <th>TYPE</th>
                <th>COMMISSION</th>
                <th>LENDER</th>
                <th>TITLE CHOICE</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="sale in formattedSales" :key="sale.id" v-on:click="show(sale)">
                <td>{{sale.closing_date}}</td>
                <td>{{sale.agent_name}}</td>
                <td>{{sale.client_name}}</td>
                <td>{{sale.address}}</td>
                <td>{{sale.sale_price}}</td>
                <td>{{sale.type}}</td>
                <td>{{sale.total_commission}}</td>
                <td>{{sale.mortgage_choice}}</td>
                <td>{{sale.title_choice}}</td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="border-top: black solid 2px; font-weight: bolder">{{ total_sale_price }}</td>
                <td></td>
                <td style="border-top: black solid 2px; font-weight: bolder">{{ total_commission }}</td>
              </tr>
            </tbody>
          </table>
            <div style="font-size: 18px" v-if="sales.length < 1">
                No results found.
            </div>
        </div>
      </div>
    </div>
    <detail-sale v-on:deletedSale="getSales(null)"></detail-sale>
  </div>
</template>

<script>
import SearchComponent from "./SearchComponent.vue";
import SearchComponent2 from "./SearchComponent2.vue";
import DetailSale from "./DetailSalesComponent.vue";

export default {
  mounted() {
    this.getSales(null);
    this.getAllDropdowns();
  },
  beforeCreate() {
    this.$loading(true);
  },
  components: {
    SearchComponent,
      SearchComponent2,
      DetailSale
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
    total_sale_price: 0,
    total_commission: 0,
      user: {
        isAdmin: false
      },
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      },
      search_terms:[],
        search_bys: [],
        dates: [],
      all_agents: [],
      all_cities: [],
      all_types: [],
      all_mortgages: [],
      all_titles: [],
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
      }
      else if (myArg.search_by === "address")
        {
            this.$loading(true);
            req = {
                method: "post",
                url: "/api/sales",
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + token
                },
                data: {
                    search_by: myArg.search_by,
                    search_term: myArg.term
                }
            };
        }
      else {
          this.$loading(true);
        req = {
          method: "post",
          url: "/api/sales",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token
          },
          data: {
            by: this.search_by,
            search_term: myArg.term
          }
        };
      }

      axios(req).then(resp => {
        this.sales = resp.data.sales;
        this.total_sale_price = resp.data.total_sale_price;
        this.total_commission = resp.data.total_commission;
        this.user.isAdmin = resp.data.req.isAdmin;
        this.$loading(false);
      });
    },
    searchSales(e){
      	// this.$loading(true);
        let bdate = $('#beginDate').val();
        let edate = $('#endDate').val();
        let search = [];
        let by = [];
        let elems = document.getElementsByClassName('custom-select');
        Array.from(elems).forEach(function (element){
            search.push(element.value);
            by.push(element.id);
        });

      	if(
      		bdate === '' &&
      		edate === '' &&
      		search[0] === '' &&
          search[1] === '' &&
          search[2] === ''
      	){
      		alert("Nothing selected to search");
          this.$loading(false);
      		return false;
      	}

      	let token = this.getCookie("token");
        let req = {
          method: "post",
          url: "/api/sales",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token
          },
          data: {
            search_by: by,
            search_term: search,
              beginDate: bdate,
              endDate: edate
          }
      };
        axios(req).then(resp => {
          this.sales = resp.data.sales;
          this.total_sale_price = Number(resp.data.total_sale_price).toLocaleString(
		        "en-US",
		        this.numberFormat
		      );
          this.total_commission = Number(resp.data.total_commission).toLocaleString(
		        "en-US",
		        this.numberFormat
		      );
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
    },
    getAllDropdowns() {
      $.ajax({
        type: "GET",
        url: "/api/allDropdowns"
      }).done((resp, status) => {
        this.all_types = resp.type_of_sales;
        this.all_agents = resp.agents;
        this.all_cities = resp.cities;
        this.all_mortgages = resp.mortgage_names;
        this.all_titles = resp.title_names;
      });
    }
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
		sale.total_commission = Number(sale.total_commission).toLocaleString(
		  "en-US",
		  this.numberFormat
		);
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
