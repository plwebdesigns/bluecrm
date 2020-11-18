<template>
  <div class="container justify-content-sm-center">
    <div class="form-row">
      <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
      >
        <h3 class="font-slab-one">Client Info</h3>
      </div>
    </div>
    <div class="row">
      <div class="col alert alert-danger" v-if="form_errors.length > 0">
        <ul>
          <li v-for="(e, index) in form_errors" :key="index">{{e}}</li>
        </ul>
      </div>
    </div>
    <form id="myForm">
      <div class="form-row pt-2">
        <div class="col-2">
          <div class="input-group">
            <div class="input-group-prepend">
              <label for="type" class="input-group-text">Type</label>
            </div>
            <select name="type" id="type" class="custom-select" v-model="sale.type">
              <option value>--Select--</option>
              <option
                v-for="(item, index) in this.all_types"
                :key="index"
                :value="item.type"
              >{{item.type}}</option>
            </select>
          </div>
        </div>
        <div class="col-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <label class="input-group-text">Agent</label>
            </div>
            <input
              class="form-control"
              type="text"
              readonly
              :value="sale.agent[0].name"
              placeholder="Automatically Generated"
            />
          </div>
        </div>
        <div class="col-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <label for="client_name" class="input-group-text">Client Name</label>
            </div>
            <input class="form-control" type="text" id="client_name" v-model="sale.client_name" />
          </div>
        </div>
      </div>
      <div class="form-row mt-2">
        <div class="col-5">
          <div class="input-group">
            <div class="input-group-prepend">
              <label for="address" class="input-group-text">Address</label>
            </div>
            <input class="form-control" type="text" id="address" v-model="sale.address" />
          </div>
        </div>
        <div class="col-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <label for="city" class="input-group-text">City</label>
            </div>
            <select class="custom-select" v-model="sale.city">
              <option value>--Select One--</option>
              <option v-for="(city,index) in all_cities" :key="index">{{city.place_name}}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
          <h3 class="font-slab-one">Sale Info</h3>
        </div>
      </div>
      <div class="form-row mt-2">
        <div class="col-2">
          <label>Closing Date</label>
          <div class="input-group">
            <input class="form-control" type="date" id="closing_date" v-model="sale.closing_date" />
          </div>
        </div>
        <div class="col-2">
          <label>Sale Price</label>
          <div class="input-group">
            <currency-input :disabled="false" v-model="sale.sale_price"></currency-input>
          </div>
        </div>
        <div class="col-2">
          <label>Lender</label>
          <div class="input-group">
            <select class="custom-select" v-model="sale.mortgage_choice">
              <option value>--Select One--</option>
              <option
                v-for="(item, index) in this.all_mortgages"
                :key="index"
                :value="item.mortgage_names"
              >{{item.mortgage_names}}</option>
            </select>
          </div>
        </div>
        <div class="col-2">
          <label>Title Choice</label>
          <div class="input-group">
            <select class="custom-select" v-model="sale.title_choice">
              <option value>--Select One--</option>
              <option
                v-for="(item, index) in this.all_titles"
                :key="index"
                :value="item.title_names"
              >{{item.title_names}}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-row mt-2">
        <div class="col-2">
          <label>Total Commission</label>
          <div class="input-group">
            <currency-input :disabled="false" v-model="sale.total_commission"></currency-input>
          </div>
        </div>
        <div class="col-2">
          <label>Transaction Fee</label>
          <div class="input-group">
            <currency-input :disabled="false" v-model="sale.transaction_fee"></currency-input>
          </div>
        </div>
        <div class="col-2">
          <label>Blue Profit</label>
          <div class="input-group">
            <currency-input :disabled="false" v-model="sale.blue_profit"></currency-input>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
          <h3 class="font-slab-one">Commission Breakdown</h3>
        </div>
      </div>
      <div class="form-row" v-for="(a,index) in sale.agent" :key="index">
        <div class="col-3">
          <label>Agent:</label>
          <select
            name="type"
            class="custom-select"
            v-model="sale.agent[index].name"
            v-on:change="sale.agent_name = sale.agent[0].name"
          >
            <option value="null">--Select--</option>
            <option v-for="(agent, index) in all_agents" :key="index">{{agent}}</option>
          </select>
        </div>
        <div class="col-2">
          <label>Split</label>
          <i
            class="far fa-question-circle"
            data-toggle="tooltip"
            data-placement="top"
            title="Percent of commission"
          ></i>
          <div class="input-group">
            <percent-input :disabled="false" v-model="sale.agent[index].split"></percent-input>
          </div>
        </div>
        <div class="col-2">
          <div class="form-group">
            <label>Commission</label>
            <i
              class="far fa-question-circle"
              data-toggle="tooltip"
              data-placement="top"
              title="Individual commission for this sale"
            ></i>
            <currency-input :disabled="false" v-model="sale.agent[index].commission"></currency-input>
          </div>
        </div>
        <div class="col-1">
          <label>Credit</label>
          <i
            class="far fa-question-circle"
            data-toggle="tooltip"
            data-placement="top"
            title="Percent of credit for this sale, used to calculate agent performance"
          ></i>
          <div class="custom-control custom-radio">
            <input
              class="custom-control-input"
              type="radio"
              v-model="sale.agent[index].percent_of_sale"
              value="100"
              :id="'100radio-' +index"
            />
            <label class="custom-control-label" :for="'100radio-' +index">100%</label>
          </div>
          <div class="custom-control custom-radio">
            <input
              class="custom-control-input"
              type="radio"
              v-model="sale.agent[index].percent_of_sale"
              value="50"
              :id="'50radio-' +index"
            />
            <label class="custom-control-label" :for="'50radio-' +index">50%</label>
          </div>
          <div class="custom-control custom-radio">
            <input
              class="custom-control-input"
              type="radio"
              v-model="sale.agent[index].percent_of_sale"
              value="0"
              :id="'0radio-' +index"
            />
            <label class="custom-control-label" :for="'0radio-' +index">0%</label>
          </div>
        </div>
        <div class="col-2">
          <label>Split Sale</label>
          <i
            class="far fa-question-circle"
            data-toggle="tooltip"
            data-placement="top"
            title="Used for team leaders"
          ></i>
          <div class="custom-control custom-radio">
            <input
              class="custom-control-input"
              type="radio"
              v-model="sale.agent[index].split_sale"
              value="Yes"
              :id="'yesRadio-' + index"
            />
            <label class="custom-control-label" :for="'yesRadio-' + index">Yes</label>
          </div>
          <div class="custom-control custom-radio">
            <input
              class="custom-control-input"
              type="radio"
              v-model="sale.agent[index].split_sale"
              value="No"
              :id="'noRadio-' + index"
            />
            <label class="custom-control-label" :for="'noRadio-' + index">No</label>
          </div>
        </div>
      </div>
      <div class="form-inline pb-2">
        <div class="input-group">
          <button
            type="button"
            class="btn btn-outline-success"
            v-on:click="addCommissionRow()"
          >Add Agent</button>
        </div>
        <div class="input-group ml-3">
          <button
            type="button"
            @click="removeCommissionRow()"
            class="btn btn-outline-danger"
          >Remove one</button>
        </div>
      </div>
      <div class="form-row mb-2">
        <div class="col-7">
          <div class="input-group input-group-lg">
            <textarea class="form-control" v-model="sale.notes" placeholder="Notes go here...."></textarea>
          </div>
        </div>
      </div>
      <div class="form-row pb-5">
        <button id="btnSubmit" type="button" class="btn btn-info" v-on:click="validateForm()">Submit</button>
        <message-modal></message-modal>
      </div>
    </form>
  </div>
</template>

<script>
import CurrencyInput from "../CurrencyInput";
import PercentInput from "../PercentInput";

export default {
  components: {
    CurrencyInput,
    PercentInput
  },
  mounted() {
    this.getAllDropdowns();
    $(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  },
  data() {
    return {
      sale: {
        type: "",
        agent_name: "",
        client_name: "",
        sale_price: "",
        address: "",
        city: "",
        closing_date: "",
        total_commission: "",
        transaction_fee: "",
        blue_profit: "",
        title_choice: "",
        mortgage_choice: "",
        notes: "",
        agent: [
          {
            name: "",
            commission: "",
            split: "",
            percent_of_sale: "100",
            split_sale: "No"
          }
        ]
      },
      all_agents: [],
      all_cities: [],
      all_types: [],
      all_mortgages: [],
      all_titles: [],
      form_errors: []
    };
  },
  methods: {
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
    },
    addCommissionRow() {
      let i = this.sale.agent.length;
      let split_sale = this.sale.agent[i - 1].split_sale;
      if (split_sale == "Yes") {
        Vue.set(this.sale.agent, i, {
          name: "Agent name",
          commission: "",
          split: "",
          percent_of_sale: 0,
          split_sale: "Yes"
        });
      } else {
        Vue.set(this.sale.agent, i, {
          name: "Agent name",
          commission: "",
          split: "",
          percent_of_sale: 0,
          split_sale: "No"
        });
      }
    },
    removeCommissionRow() {
      let i = this.sale.agent.length - 1;
      if (i > 0) {
        Vue.delete(this.sale.agent, i);
      } else {
        this.$modal.show("messageModal", {
          data: "At least one agent must be entered",
          style: {
            class: "text-danger"
          }
        });
      }
    },
    validateForm() {
      document.getElementById("btnSubmit").disabled = true;
      let req = {
        method: "POST",
        url: "/api/add-sale",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + this.getCookie("token")
        },
        data: this.sale
      };
      axios(req)
        .then(resp => {
          if (resp.data.data === "success") {
            this.form_errors = [];
            this.form_errors.length = 0;
            this.sale = Object.assign({}, this.sale, {
              type: "",
              agent_name: "",
              client_name: "",
              sale_price: "",
              address: "",
              city: "",
              closing_date: "",
              total_commission: "",
              transaction_fee: "",
              blue_profit: "",
              title_choice: "",
              mortgage_choice: "",
              notes: "",
              agent: [
                {
                  name: "",
                  commission: "",
                  split: "",
                  percent_of_sale: "100",
                  split_sale: "No"
                }
              ]
            });
            this.$modal.show("messageModal", {
              data: "Successfully entered sale!",
              style: {
                class: "text-info"
              }
            });
            document.getElementById("btnSubmit").disabled = false;
          }
        })
        .catch(error => {
          if (error.response.status === 412) {
            this.form_errors = error.response.data.errors;
            document.getElementById("btnSubmit").disabled = false;
            this.$modal.show("messageModal", {
              data: "Please correct the errors",
              style: {
                class: "text-danger"
              }
            });
          } else {
            document.getElementById("btnSubmit").disabled = false;
            this.$modal.show("messageModal", {
              data: "There was an error with your submission",
              style: {
                class: "text-danger"
              }
            });
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
    }
  },
  computed: {
    sortAgents: function() {
      let agents = [];

      for (const key in this.all_agents) {
        agents.push(this.all_agents[key].agent_name);
      }

      return agents.sort();
    }
  }
};
</script>
<style scoped>
label {
  font-family: "Alegreya", serif;
  font-size: 16px;
}
</style>
