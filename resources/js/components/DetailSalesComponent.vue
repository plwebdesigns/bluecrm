<template>
  <div>
    <modal
      name="detailSale"
      @before-open="beforeOpen"
      height="auto"
      width="1000px"
      :scrollable="true"
      :draggable="false"
    >
      <div class="container bg-blue-realty p-3 text-white">
        <form id="detail-form">
          <h1 class="display-6 text-center text-white font-fugaz">{{sale.client_name}}</h1>
          <hr class="my-4" />
          <h4 class="font-fredricka">Client Info</h4>
          <div class="form-row pb-2">
            <div class="col">
              <label>Deal #</label>
              <input
                disabled
                readonly
                type="text"
                class="form-control-sm form-control"
                v-model="sale.id"
              />
            </div>
            <div class="col">
              <label>TYPE</label>
              <select
                id="type_select"
                v-model="sale.type"
                class="custom-select custom-select-sm editable"
                disabled
              >
                <option selected>{{sale.type}}</option>
                <option v-for="(item, index) in all_types" :key="index">{{item.type}}</option>
              </select>
            </div>
            <div class="col">
              <label>CLOSE DATE</label>
                <input type="date" class="editable form-control form-control-sm" v-model="sale.closing_date" disabled />

            </div>
          </div>
          <div class="form-row pb-2">
            <div class="col">
              <label>CLIENT</label>
              <input
                disabled
                type="text"
                class="form-control-sm form-control editable"
                v-model="sale.client_name"
              />
            </div>
            <div class="col">
              <label>ADDRESS</label>
              <input
                disabled
                type="text"
                class="form-control-sm form-control editable"
                v-model="sale.address"
              />
            </div>
            <div class="col">
              <label>CITY</label>
              <input
                disabled
                type="text"
                class="form-control-sm form-control editable"
                v-model="sale.city"
              />
            </div>
          </div>
          <hr class="my-4" />
          <h4 class="font-fredricka">Sale Info</h4>
          <div class="form-row pb-2">
            <div class="col">
              <label>SALE PRICE</label>
              <currency-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="sale.sale_price"
              ></currency-input>
            </div>
            <div class="col">
              <label>TRANS FEE</label>
              <currency-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="sale.transaction_fee"
              ></currency-input>
            </div>
            <div class="col">
              <label>BLUE REALTY SPLIT</label>
              <currency-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="sale.blue_profit"
              ></currency-input>
            </div>
          </div>
          <div class="form-row">
            <div class="col">
              <label>COMMISSION</label>
              <currency-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="sale.total_commission"
              ></currency-input>
            </div>
            <div class="col">
              <label>LENDER</label>
              <input
                disabled
                class="form-control form-control-sm editable"
                type="text"
                v-model="sale.mortgage_choice"
              />
            </div>
            <div class="col">
              <label>TITLE</label>
              <input
                disabled
                class="form-control form-control-sm editable"
                type="text"
                v-model="sale.title_choice"
              />
            </div>
          </div>
          <hr class="my-4" />
          <h4 class="font-fredricka">Commission</h4>
          <div class="form-row p-1" v-for="(a, index) in agents" :key="index">
            <div class="col-2">
              <label>Name</label>
              <select
                name="agent_name"
                class="custom-select custom-select-sm"
                v-model="a.agent_name"
                disabled
              >
                <option>{{a.agent_name}}</option>
              </select>
            </div>
            <div class="col">
              <label>Commission</label>
              <currency-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="a.pivot.commission"
              ></currency-input>
            </div>
            <div class="col">
              <label>Split</label>
              <percent-input
                class="form-control-sm editable"
                :disabled="true"
                v-model="a.pivot.split"
                :id="'split' + index"
              ></percent-input>
            </div>
            <div class="col">
              <label>Sale Credit</label>
              <currency-input
                class="form-control-sm"
                :disabled="true"
                v-model="a.pivot.sale_credit"
              ></currency-input>
            </div>
            <div class="col-4">
              <p class="m-0">Percent of Sale</p>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="a.pivot.percent_of_sale"
                  value="1"
                  :id="'100radio-' +index"
                  disabled
                />
                <label class="custom-control-label" :for="'100radio-' +index">100%</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="a.pivot.percent_of_sale"
                  value="0.5"
                  :id="'50radio-' +index"
                  disabled
                />
                <label class="custom-control-label" :for="'50radio-' +index">50%</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="a.pivot.percent_of_sale"
                  value="0"
                  :id="'0radio-' +index"
                  disabled
                />
                <label class="custom-control-label" :for="'0radio-' +index">0%</label>
              </div>
              <button
                type="button"
                class="btn btn-danger btn-sm closeIcon"
                style="padding-bottom: 0px;padding-top: 0px;padding-right: 4px;padding-left: 4px;"
                @click="deleteEntry(index)"
              >
                <i class="fas fa-times-circle"></i>
              </button>
            </div>
          </div>
          <div class="row pt-4" v-if="errors.length > 0">
            <div class="col">
              <ul>
                <li
                  class="text-danger font-weight-bolder"
                  style="font-size: larger"
                  v-for="(e,index) in errors"
                  :key="index"
                >{{e}}</li>
              </ul>
            </div>
          </div>
          <div class="row pt-4 pb-4">
            <div class="col-2">
              <button id="closebtn" type="button" class="btn btn-sm btn-light" @click="hide">Close</button>
              <button
                type="button"
                class="btn btn-sm btn-light"
                @click="editFields"
                id="editbtn"
              >Edit</button>
              <button
                type="button"
                class="btn btn-sm btn-danger"
                @click="save"
                hidden
                id="savebtn"
              >Save</button>
              <button
                type="button"
                hidden
                class="btn btn-sm btn-light"
                @click="cancelEdit"
                id="cancelbtn"
              >Cancel</button>
              <div id="successfulEdit"></div>
            </div>
            <div class="col-2">
              <button
                id="addRowbtn"
                type="button"
                class="btn btn-sm btn-success"
                @click="addCommissionRow()"
              >Add Commission</button>
            </div>
            <div class="col-1">
              <button type="button" class="btn btn-sm btn-dark" @click="generatePdf()">Print</button>
            </div>
          </div>
          <h4 v-if="isAddingRow">New Commission Row:</h4>
          <div class="form-row pb-3" v-if="isAddingRow">
            <div class="col">
              <label>Name</label>
              <select
                class="custom-select custom-select-sm"
                v-model="new_agent.agent_name"
                id="newSelect"
              >
                <option selected value="null">--Select One--</option>
                <option
                  v-for="(item, index) in all_agents"
                  :key="index"
                  :value="item"
                >{{item}}</option>
              </select>
            </div>
            <div class="col">
              <label>Commission</label>
              <currency-input
                class="form-control-sm"
                v-model="new_agent.commission"
                id="newCommission"
              ></currency-input>
            </div>
            <div class="col">
              <label>Split</label>
              <percent-input class="form-control-sm" v-model="new_agent.split" id="newSplit"></percent-input>
            </div>
            <div class="col-4">
              <p class="m-0">Percent of Sale</p>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="new_agent.percent_of_sale"
                  value="1"
                  id="100ValueRadio"
                />
                <label class="custom-control-label" for="100ValueRadio">100%</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="new_agent.percent_of_sale"
                  value="0.5"
                  id="50ValueRadio"
                />
                <label class="custom-control-label" for="50ValueRadio">50%</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input
                  class="custom-control-input editable"
                  type="radio"
                  v-model="new_agent.percent_of_sale"
                  value="0"
                  id="0ValueRadio"
                />
                <label class="custom-control-label" for="0ValueRadio">0%</label>
              </div>
            </div>
          </div>
            <div class="form-row">
                <div class="col-6">
                    <label>Notes</label>
                    <textarea class="form-control" rows="5" v-model="sale.notes"></textarea>
                </div>
            </div>
          <div class="form-row" v-if="isAddingRow">
            <div class="col-2">
              <button
                id="addCommissionBtn"
                type="button"
                class="btn btn-sm btn-danger"
                @click="addCommission()"
              >Add</button>
              <button
                id="cancelCommissionBtn"
                type="button"
                class="btn btn-sm btn-light"
                @click="cancelCommissionRow()"
              >Cancel</button>
            </div>
          </div>
          <message-modal></message-modal>
        </form>
      </div>
    </modal>
  </div>
</template>

<script>
import CurrencyInput from "./CurrencyInput";
import DateInput from "./DateInput";
import PercentInput from "./PercentInput";

export default {
  components: {
    PercentInput,
    CurrencyInput,
    DateInput
  },
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
      agents: [
        {
          agent_name: "",
          id: "",
          pivot: {
            commission: "",
            split: ""
          }
        }
      ],
      new_agent: {
        agent_name: null,
        commission: "",
        split: "",
        percent_of_sale: 1
      },
      isAddingRow: false,
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      },
      errors: [],
      all_types: [],
      all_agents: []
    };
  },
  methods: {
    hide() {
      this.$modal.hide("detailSale");
      this.errors = [];
      $("#successfulEdit").html("");
      this.isAddingRow = false;
    },
    beforeOpen(event) {
      // this.sale = event.params.data;
        let id = (event.params.data.sale_id) ? event.params.data.sale_id : event.params.data.id;
      let token = this.getCookie("token");
      let req = {
        method: "post",
        url: "/api/detail",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: {
          id: id
        }
      };
      axios(req).then(resp => {
        this.sale = resp.data.sale;
        this.agents = resp.data.users;
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
    editFields() {
      $("input.editable").attr("disabled", false);
      this.getAllDropdowns();
      $("select.editable").attr("disabled", false);
      document.getElementById("editbtn").hidden = true;
      document.getElementById("savebtn").hidden = false;
      document.getElementById("cancelbtn").hidden = false;
      document.getElementById("closebtn").hidden = true;
      $("#successfulEdit").html("");
      $(".closeIcon").attr("hidden", true);
    },
    cancelEdit() {
      $("input.editable").attr("disabled", true);
      $("select.editable").attr("disabled", true);
      document.getElementById("editbtn").hidden = false;
      document.getElementById("savebtn").hidden = true;
      document.getElementById("cancelbtn").hidden = true;
      document.getElementById("closebtn").hidden = false;
      $(".closeIcon").attr("hidden", false);
    },
    save() {
      $("#savebtn").attr("disabled", true);
      let token = this.getCookie("token");
      this.errors = [];
      $.ajax({
        type: "POST",
        url: "/api/updatesale",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: {
          sale: this.sale,
          agent: this.agents
        }
      })
        .done(resp => {
          //Buttons
          $("#savebtn").attr("hidden", true);
          $("#editbtn").attr("hidden", false);
          $("#cancelbtn").attr("hidden", true);
          $("#closebtn").attr("hidden", false);
          // Inputs
          $("input.editable").attr("disabled", true);
          $("select.editable").attr("disabled", true);
          //Icons
          $(".closeIcon").attr("hidden", true);
          if (resp.success) {
            $("#successfulEdit").append(
              "<strong>Sucessfully edited info!</strong>"
            );
          }
        })
        .fail(err => {
          $("#savebtn").attr("disabled", false);
          this.errors = err.responseJSON.errors;
        });
    },
    deleteEntry(i) {
      let chk = confirm("Are you sure you want to delete that?");
      if (chk) {
        // Request the row be detached from Sale model
        $.ajax({
          type: "POST",
          url: "/api/deleteSaleUser",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + this.getCookie("token")
          },
          data: {
            sale_id: this.sale.id,
            user_id: this.agents[i].id
          }
        }).done(resp => {
          this.$modal.show("messageModal", {
            data: "Successfully deleted!",
            style: {
              class: "text-danger"
            }
          });
          this.hide();
        });
      }
    },
    addCommissionRow() {
      $("#addRowbtn, #editbtn, #closebtn").attr("disabled", true);
      this.isAddingRow = true;
      this.getAllDropdowns();
    },
    cancelCommissionRow() {
      this.isAddingRow = false;
      $("#addRowbtn, #editbtn, #closebtn").attr("disabled", false);
      $("#newSelect").val("null");
      this.new_agent.agent_name = null;
      $("#newCommission, #newSplit").val("");
      this.new_agent.commission = "";
      this.new_agent.split = "";
    },
    addCommission() {
      this.errors = [];
      $("#addCommissionBtn").attr("disabled", true);
      $.ajax({
        type: "POST",
        url: "/api/addCommission",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + this.getCookie("token")
        },
        data: {
          sale_id: this.sale.id,
          sale_info: this.new_agent
        }
      })
        .done(resp => {
          this.$modal.show("messageModal", {
            data: "Success!",
            style: {
              class: "text-danger"
            }
          });
          this.cancelCommissionRow();
          this.hide();
        })
        .fail(err => {
          this.errors = err.responseJSON.errors;
          $("#addCommissionBtn").attr("disabled", false);
        });
    },
    getAllDropdowns() {
      $.ajax({
        type: "GET",
        url: "/api/allDropdowns"
      }).done(resp => {
        this.all_types = resp.type_of_sales;
        this.all_agents = resp.agents;
      });
    },
    generatePdf() {
      window.open("/detail_pdf/" + this.sale.id);
    }
  }
};
</script>
