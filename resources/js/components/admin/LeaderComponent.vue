<template>
  <div>
    <div class="row pb-4 mt-4">
      <div class="col-3">
        <SelectYearComponent v-on:production_year="getEmployees(production_year = $event)"></SelectYearComponent>
      </div>
      <div class="col-3">
        <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text bg-blue-realty text-white">Office Location</label>
        </div>
        <select id="office_location" class="custom-select" v-on:change="getEmployees(production_year)" v-model="office_location">
          <option v-for="(location) in office_locations">{{ location }}</option>
        </select>
      </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <h3 class="text-center font-fugaz">Quarter 1</h3>
        <table class="table table-sm border-black table-striped">
          <thead>
            <tr class="bg-blue-realty">
              <th>RANK</th>
              <th>AGENT</th>
              <th>AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-black"
              v-for="(employee, index) in sortedQ1"
              :key="index"
              v-bind:class="[{'bg-belowten': index>9}, {'bg-highlight': index<=9} ]"
            >
              <td class="border-black">{{index+1}}</td>
              <td class="border-black">{{employee.agent}}</td>
              <td class="border-black">${{Number(employee.total).toLocaleString()}}</td>
            </tr>
            <tr style="border-top: black solid 2px; font-weight: bolder">
              <td></td>
              <td></td>
              <td>${{Number(q1_total).toLocaleString()}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col">
        <h3 class="text-center font-fugaz">Quarter 2</h3>
        <table class="table table-sm border-black table-striped">
          <thead>
            <tr class="bg-blue-realty border-black">
              <th>RANK</th>
              <th>AGENT</th>
              <th>AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-black"
              v-for="(employee, index) in sortedQ2"
              :key="index"
              v-bind:class="[{'bg-belowten': index>9}, {'bg-highlight': index<=9} ]"
            >
              <td class="border-black">{{index+1}}</td>
              <td class="border-black">{{employee.agent}}</td>
              <td class="border-black">${{Number(employee.total).toLocaleString()}}</td>
            </tr>
            <tr style="border-top: black solid 2px; font-weight: bolder">
              <td></td>
              <td></td>
              <td>${{Number(q2_total).toLocaleString()}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col">
        <h3 class="text-center font-fugaz">Quarter 3</h3>
        <table class="table table-sm border-black table-striped">
          <thead>
            <tr class="bg-blue-realty">
              <TH>RANK</TH>
              <th>AGENT</th>
              <th>AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-black"
              v-for="(employee, index) in sortedQ3"
              :key="index"
              v-bind:class="[{'bg-belowten': index>9}, {'bg-highlight': index<=9} ]"
            >
              <td class="border-black">{{index+1}}</td>
              <td class="border-black">{{employee.agent}}</td>
              <td class="border-black">${{Number(employee.total).toLocaleString()}}</td>
            </tr>
            <tr style="border-top: black solid 2px; font-weight: bolder">
              <td></td>
              <td></td>
              <td>${{Number(q3_total).toLocaleString()}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col">
        <h3 class="text-center font-fugaz">Quarter 4</h3>
        <table class="table table-sm border-black table-striped">
          <thead>
            <tr class="bg-blue-realty">
              <TH>RANK</TH>
              <th>AGENT</th>
              <th>AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-black"
              v-for="(employee, index) in sortedQ4"
              :key="index"
              v-bind:class="[{'bg-belowten': index>9}, {'bg-highlight': index<=9} ]"
            >
              <td class="border-black">{{index+1}}</td>
              <td class="border-black">{{employee.agent}}</td>
              <td class="border-black">${{Number(employee.total).toLocaleString()}}</td>
            </tr>
            <tr style="border-top: black solid 2px; font-weight: bolder">
              <td></td>
              <td></td>
              <td>${{Number(q4_total).toLocaleString()}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col">
        <h3 class="text-center font-fugaz">YTD</h3>
        <table class="table table-sm border-black table-striped">
          <thead>
            <tr class="bg-blue-realty">
              <TH>RANK</TH>
              <th>AGENT</th>
              <th>AMOUNT</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="border-black"
              v-for="(employee, index) in sorted_ytd"
              :key="index"
              v-bind:class="[{'bg-belowten': index>9}, {'bg-highlight': index<=9} ]"
            >
              <td class="border-black">{{index+1}}</td>
              <td class="border-black">{{employee.agent}}</td>
              <td class="border-black">${{Number(employee.total).toLocaleString()}}</td>
            </tr>
            <tr style="border-top: black solid 2px; font-weight: bolder">
              <td></td>
              <td></td>
              <td>${{Number(ytd_total).toLocaleString()}}</td>
            </tr>
          </tbody>
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
    this.getEmployees(this.production_year);
  },
  data() {
    return {
      quarter1Ten: {
        agent: "",
        total: 0
      },
      quarter2Ten: {
        agent: "",
        total: 0
      },
      quarter3Ten: {
        agent: "",
        total: 0
      },
      quarter4Ten: {
        agent: "",
        total: 0
      },
      ytd_sales: {
        agent: "",
        total: 0
      },
      q1_total: 0,
      q2_total: 0,
      q3_total: 0,
      q4_total: 0,
      ytd_total: 0,
      user: {
        isAdmin: false
      },
      style: {
        background_color: "yellow"
      },
      production_year: new Date().getFullYear(),
      office_locations: [],
      office_location: ""
    };
  },
  methods: {
    getEmployees(prod_year) {
      let token = this.getCookie("token");
      axios({
        method: "get",
        url: "/api/leaderboard",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        params: {
          production_year: prod_year,
          office_location: this.office_location
        }
      })
        .then(resp => {
          this.quarter1Ten = resp.data.quarter1Ten;
          this.quarter2Ten = resp.data.quarter2Ten;
          this.quarter3Ten = resp.data.quarter3Ten;
          this.quarter4Ten = resp.data.quarter4Ten;
          this.ytd_sales = resp.data.ytd_sales;
          this.q1_total = resp.data.q1Total;
          this.q2_total = resp.data.q2Total;
          this.q3_total = resp.data.q3Total;
          this.q4_total = resp.data.q4Total;
          this.ytd_total = resp.data.ytdTotal;
          this.user.isAdmin = resp.data.req.isAdmin;
          this.office_locations = resp.data.office_locations;
          this.office_location = resp.data.office_location;
          this.$loading(false);
        })
        .catch(function(error) {
          if (error.response.status == 401) {
            alert(
              "You are Blue Realty, or you are an enemy of Blue Realty, choose!"
            );
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
        while (c.charAt(0) === " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }
  },
  computed: {
    sortedQ1() {
      let employees = [];

      for (const key in this.quarter1Ten) {
        if (this.quarter1Ten.hasOwnProperty(key)) {
          const element = this.quarter1Ten[key];
          if (element.total != 0) {
            employees.push(element);
          }
        }
      }
      employees.sort(function(a, b) {
        return b.total - a.total;
      });

      return employees.slice(0, 25);
    },
    sortedQ2() {
      let employees = [];

      for (const key in this.quarter2Ten) {
        if (this.quarter2Ten.hasOwnProperty(key)) {
          const element = this.quarter2Ten[key];
          if (element.total != 0) {
            employees.push(element);
          }
        }
      }
      employees.sort(function(a, b) {
        return b.total - a.total;
      });

      return employees.slice(0, 25);
    },
    sortedQ3() {
      let employees = [];

      for (const key in this.quarter3Ten) {
        if (this.quarter3Ten.hasOwnProperty(key)) {
          const element = this.quarter3Ten[key];
          if (element.total != 0) {
            employees.push(element);
          }
        }
      }
      employees.sort(function(a, b) {
        return b.total - a.total;
      });

      return employees.slice(0, 25);
    },
    sortedQ4() {
      let employees = [];

      for (const key in this.quarter4Ten) {
        if (this.quarter4Ten.hasOwnProperty(key)) {
          const element = this.quarter4Ten[key];

          if (element.total != 0) {
            employees.push(element);
          }
        }
      }
      employees.sort(function(a, b) {
        return b.total - a.total;
      });
      return employees.slice(0, 25);
    },
    sorted_ytd() {
      let employees = [];

      for (const key in this.ytd_sales) {
        if (this.ytd_sales.hasOwnProperty(key)) {
          const element = this.ytd_sales[key];

          if (element.total != 0) {
            employees.push(element);
          }
        }
      }
      employees.sort(function(a, b) {
        return b.total - a.total;
      });
      return employees.slice(0, 25);
    }
  }
};
</script>

