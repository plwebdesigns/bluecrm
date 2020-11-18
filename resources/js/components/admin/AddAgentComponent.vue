<template>
  <div class="container">
    <div class="row pt-4 justify-content-center">
      <div class="col-3"></div>
      <div class="col-6">
        <div class="card">
          <div class="card-header text-center bg-blue-realty text-white">Register New Agent</div>
          <div class="card-body">
            <form id="addAgentForm" method="POST">
              <div class="form-row pt-2">
                <div class="col-6">
                  <label>Agent Name:</label>
                  <input type="text" class="form-control" v-model="agent.name" required />
                </div>
                <div class="col-6">
                  <label>Title:</label>
                  <select class="custom-select" name="title" v-model="agent.title" required>
                    <option value="null">---Please Select---</option>
                    <option
                      v-for="(item, index) in emp_titles"
                      :key="index"
                      :value="item.title"
                    >{{item.title}}</option>
                  </select>
                </div>
              </div>
              <div class="form-row pt-2">
                <div class="col-6">
                  <label>Phone:</label>
                  <input type="text" class="form-control" v-model="agent.phone" required />
                </div>
                <div class="col-6">
                  <label>Email:</label>
                  <input type="email" class="form-control" v-model="agent.email" required />
                </div>
              </div>
              <div class="form-row pt-4">
                  <div class="col">
                      <label>DOB (optional):</label>
                      <input type="text" class="form-control" v-model="agent.dob" />
                  </div>
                <div class="col-6">
                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input
                      v-model="agent.isAdmin"
                      type="checkbox"
                      id="customRadioInline1"
                      name="customRadioInline1"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="customRadioInline1">Admin</label>
                  </div>

                  <div class="custom-control custom-checkbox custom-control-inline">
                    <input
                      v-model="agent.isManager"
                      type="checkbox"
                      id="customRadioInline2"
                      name="customRadioInline1"
                      class="custom-control-input"
                    />
                    <label class="custom-control-label" for="customRadioInline2">Team Leader</label>
                  </div>
                </div>
              </div>
              <div class="form-row pt-2">
                <div class="col-6">
                  <label>Password:</label>
                  <input v-model="agent.password" type="password" class="form-control" required />
                </div>
                <div class="col-6">
                  <label>Confirm Password:</label>
                  <input v-model="agent.confirm_pass" type="password" class="form-control" required />
                </div>
              </div>
              <div class="form-row pt-3">
                <div class="col-2">
                  <button id="savebtn" type="button" class="btn btn-primary" v-on:click="validateForm()">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col">
        <ul>
          <li v-for="(error, index) in errors" :key="index" class="text-danger">{{error}}</li>
        </ul>
      </div>
    </div>
    <message-modal></message-modal>
  </div>
</template>

<script>
export default {
  mounted() {
    this.getAllDropdowns();
  },
  data() {
    return {
      agent: {
        name: "",
        title: null,
        phone: "",
        email: "",
          dob: "",
        isAdmin: false,
        isManager: false,
        password: "",
        confirm_pass: ""
      },
      emp_titles: [],
      errors: []
    };
  },
  methods: {
      getAllDropdowns() {
          let token = this.getCookie("token");
          $.ajax({
              type: "GET",
              url: "/api/agent_titles",
              headers: {
                  Accept: "application/json",
                  Authorization: "Bearer " + token
              },
          }).done((resp) => {
              this.emp_titles = resp.agent_titles;
          });
      },
    validateForm: function() {
      let token = this.getCookie("token");
      $('#savebtn').attr('disabled', true);
      $.ajax({
        type: "post",
        url: "/api/add-agent",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        data: this.agent
      })
        .done(resp => {
          this.errors = [];
          if (resp.msg === "success") {
            this.$modal.show("messageModal", {
              data: "Successfully entered new agent!",
              style: {
                class: "text-info"
              }
            });
          }
            $('#savebtn').attr('disabled', false);
        })
        .fail(resp => {
          this.errors = resp.responseJSON.errors;
            $('#savebtn').attr('disabled', false);
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
  }
};
</script>
