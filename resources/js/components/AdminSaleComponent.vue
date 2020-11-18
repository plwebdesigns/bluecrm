<template>
  <div>
    <modal
      name="adminSales"
      @before-open="beforeOpen"
      height="auto"
      width="800px"
      :scrollable="true"
      :draggable="true"
    >
      <div class="container bg-blue-realty p-3 text-white">
        <form id="detailForm">
          <h1 class="display-6 text-center text-white font-fugaz">{{sale.client_name}}</h1>
          <hr class="my-4" />
          <h4 class="font-fredricka">Client Info</h4>
          <div class="row pb-2">
            <div class="col">
              <label>Deal #</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.id" />
            </div>
            <div class="col">
              <label>Type</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.type" />
            </div>
            <div class="col">
              <label>Closing Date</label>
              <input
                type="text"
                class="form-control-sm form-control"
                v-model="new Date(sale.closing_date).toLocaleDateString()"
              />
            </div>
          </div>
          <div class="row pb-2">
            <div class="col">
              <label>Client Name</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.client_name" />
            </div>
            <div class="col">
              <label>Address</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.address" />
            </div>
            <div class="col">
              <label>City</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.city" />
            </div>
          </div>
          <hr class="my-4" />
          <h4 class="font-fredricka">Sale Info</h4>
          <div class="row">
            <div class="col">
              <label>Sale Price:</label>
              <div class="input-group flex-nowrap">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="Number(sale.sale_price).toLocaleString('en-US', numberFormat)"
                />
              </div>
            </div>
            <div class="col">
              <label>Total Commission:</label>
              <div class="input-group flex-nowrap">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="Number(sale.total_commission).toLocaleString('en-US', numberFormat)"
                />
              </div>
            </div>
            <div class="col">
              <label>Blue Realty Split:</label>
              <div class="input-group flex-nowrap">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="Number(sale.blue_profit).toLocaleString('en-US', numberFormat)"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>Transaction Fee:</label>
              <div class="input-group flex-nowrap">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  v-model="sale.transaction_fee.toLocaleString('en-US', numberFormat)"
                />
              </div>
            </div>
            <div class="col">
              <label>Title Choice:</label>
              <input type="text" class="form-control-sm form-control" v-model="sale.title_choice" />
            </div>
            <div class="col">
              <label>Lender:</label>
              <input
                type="text"
                class="form-control-sm form-control"
                v-model="sale.mortgage_choice"
              />
            </div>
          </div>
          <hr class="my-4" />
          <h4 class="font-fredricka">Commission</h4>
          <table class="table table-sm table-info">
            <thead class="thead-dark">
              <tr>
                <th>AGENT</th>
                <th>COMMISSION</th>
                <th>SPLIT</th>
              </tr>
            </thead>
            <tbody class="text-dark">
              <tr v-for="(c, index) in commission" :key="index">
                <td>{{c.name}}</td>
                <td>{{Number(c.commission).toLocaleString('en-US', numberFormat)}}</td>
                <td>{{Number(c.split)*100}}%</td>
              </tr>
            </tbody>
          </table>
          <div class="row pt-4 pb-4">
            <div class="col">
              <button type="button" class="btn btn-sm btn-light" @click="hide">Close</button>
              <button type="button" class="btn btn-sm btn-success" @click="printSale()">Print</button>
            </div>
          </div>
        </form>
      </div>
    </modal>
  </div>
</template>

<script>
export default {
  mounted() {},
  data() {
    return {
      sale: {
        id: 0,
        type: "",
        closing_date: "",
        agent_name: "",
        client_name: "",
        address: "",
        city: "",
        sale_price: 0,
        transaction_fee: 0,
        blue_profit: 0,
        total_commission: 0,
        title_choice: "",
        mortgage_choice: ""
      },
      commission: {
        name: "",
        commission: 0,
        split: 0
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
    hide() {
      this.$modal.hide("adminSales");
    },
    beforeOpen(event) {
      this.sale = event.params.data;

      let token = this.getCookie("token");
      let req = {
        method: "post",
        url: "https://thebluecrm.com/api/commission",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: {
          id: this.sale.id
        }
      };
      axios(req).then(resp => {
        this.commission = resp.data.commission;
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
    printSale() {
      window.open("/detail_pdf/" + this.sale.id, "_blank");
    }
  }
};
</script>
