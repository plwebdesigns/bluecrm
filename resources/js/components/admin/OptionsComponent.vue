<template>
  <div class="container pt-3">
    <h1 class="font-fredricka mb-4">Options</h1>
    <div class="row">
      <div class="col-3">
        <div class="card">
          <div class="card-header">Ignored Agents</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              id="ignoredSelect"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_agents"
            >
              <option v-for="(agent,index) in ignored_agents" :key="index">{{agent}}</option>
            </select>
            <div class="form-group">
              <input
                id="addAgentText"
                type="text"
                class="form-control form-control-sm"
                placeholder="Agent Name"
                @keyup.enter="add(ignored_agents,'addAgentText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(ignored_agents,'addAgentText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_agents, ignored_agents)"
            >Remove</button>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <div class="card-header">Type of Sales</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              id="typesSelect"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_types"
            >
              <option v-for="(type,index) in types_of_sales" :key="index">{{type}}</option>
            </select>
            <div class="form-group">
              <input
                id="addTypeText"
                type="text"
                class="form-control form-control-sm"
                placeholder="Type of Sale"
                @keyup.enter="add(types_of_sales, 'addTypeText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(types_of_sales, 'addTypeText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_types, types_of_sales)"
            >Remove</button>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <div class="card-header">Cities</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              id="citySelect"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_cities"
            >
              <option v-for="(city,index) in cities" :key="index">{{city}}</option>
            </select>
            <div class="form-group">
              <input
                id="addCityText"
                type="text"
                class="form-control form-control-sm"
                placeholder="New City"
                @keyup.enter="add(cities, 'addCityText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(cities, 'addCityText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_cities, cities)"
            >Remove</button>
          </div>
        </div>
      </div>
      <div class="col-3" v-if="selected_cities.length >= 1">
        <h4 class="font-fugaz">Selected:</h4>
        <table>
          <tbody>
            <tr v-for="(item, index) in selected_cities" :key="index">
              <td>{{item}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-3">
        <div class="card">
          <div class="card-header">Lender</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              id="citySelect"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_mortgages"
            >
              <option v-for="(m,index) in mortgages" :key="index">{{m}}</option>
            </select>
            <div class="form-group">
              <input
                id="addMortgageText"
                type="text"
                class="form-control form-control-sm"
                placeholder="Lender"
                @keyup.enter="add(mortgages, 'addMortgageText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(mortgages, 'addMortgageText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_mortgages, mortgages)"
            >Remove</button>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <div class="card-header">Title Choices</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              id="citySelect"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_titles"
            >
              <option v-for="(title,index) in titles" :key="index">{{title}}</option>
            </select>
            <div class="form-group">
              <input
                id="addTitleText"
                type="text"
                class="form-control form-control-sm"
                placeholder="Lender"
                @keyup.enter="add(titles, 'addTitleText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(titles, 'addTitleText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_titles, titles)"
            >Remove</button>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card">
          <div class="card-header">Employee Titles</div>
          <div class="card-body">
            <select
              class="custom-select mb-3"
              multiple
              style="padding-bottom: 100px"
              v-model="selected_emp_titles"
            >
              <option v-for="(title,index) in emp_titles" :key="index">{{title}}</option>
            </select>
            <div class="form-group">
              <input
                id="addEmpTitleText"
                type="text"
                class="form-control form-control-sm"
                placeholder="Employee Title"
                @keyup.enter="add(emp_titles, 'addEmpTitleText')"
              />
            </div>
            <button
              class="btn btn-outline-info btn-sm"
              type="button"
              @click="add(emp_titles, 'addEmpTitleText')"
            >Add</button>
            <button
              class="btn btn-outline-danger btn-sm"
              type="button"
              @click="remove(selected_emp_titles, emp_titles)"
            >Remove</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-1">
        <button type="button" class="btn btn-success" @click="updateOptions()">
          <span id="saveBtn">Save</span>
          <span
            id="spinner"
            class="spinner-border spinner-border-sm"
            role="status"
            aria-hidden="true"
            hidden
          ></span>
        </button>
      </div>
    </div>
    <message-modal></message-modal>
  </div>
</template>
<script>
export default {
  mounted() {
    this.getAllLists();
  },
  beforeCreate() {
    this.$loading(true);
  },
  data() {
    return {
      ignored_agents: [],
      types_of_sales: [],
      cities: [],
      mortgages: [],
      titles: [],
      emp_titles: [],
      selected_cities: [],
      selected_agents: [],
      selected_types: [],
      selected_mortgages: [],
      selected_titles: [],
      selected_emp_titles: []
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
    getAllLists() {
      let token = this.getCookie("token");
      $.ajax({
        type: "get",
        url: "/api/allLists",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        }
      }).done(resp => {
        this.ignored_agents = resp.ignored_agents;
        this.types_of_sales = resp.types_of_sales;
        this.cities = resp.cities;
        this.mortgages = resp.mortgages;
        this.titles = resp.titles;
        this.emp_titles = resp.emp_titles;
        this.$loading(false);
      });
    },
    add(arr, txtbox) {
      arr.push($("#" + txtbox).val());
      $("#" + txtbox).val("");
      $("#" + txtbox).attr("placeholder", "Agent Name");
    },
    remove(arr1, arr2) {
      arr1.forEach(ag => {
        arr2.splice(
          arr2.findIndex(val => val === ag),
          1
        );
      });
    },
    updateOptions() {
      $("#saveBtn").attr("hidden", true);
      $("#spinner").attr("hidden", false);
      let token = this.getCookie("token");

      $.ajax({
        type: "post",
        url: "/api/updateOptions",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: {
          ignored_agents: this.ignored_agents,
          types_of_sales: this.types_of_sales,
          cities: this.cities,
          mortgages: this.mortgages,
          titles: this.titles,
          emp_titles: this.emp_titles
        }
      })
        .done(resp => {
          if (resp.msg === "success") {
            this.$modal.show("messageModal", {
              data: "Successfully saved options!",
              style: {
                class: "text-info"
              }
            });
          }
          $("#saveBtn").attr("hidden", false);
          $("#spinner").attr("hidden", true);
        })
        .fail(resp => {
          $("#saveBtn").attr("hidden", false);
          $("#spinner").attr("hidden", true);
          this.$modal.show("messageModal", {
            data: "Something went wrong",
            style: {
              class: "text-danger"
            }
          });
        });
    }
  }
};
</script>
<style scoped>
.card-header {
  font-family: "Alegreya", serif;
  font-size: 16px;
  text-align: center;
}
.table tr td {
  line-height: 15px;
}
</style>
