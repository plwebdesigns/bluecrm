<template>
  <div>
    <modal name="changePass" height="auto" width="400px" :scrollable="true">
      <div class="container bg-blue-realty p-3 text-white">
        <h3 class="pb-3 font-fredricka">Change Password Form</h3>
        <form>
          <div class="form-group">
            <label for="currentPassword">Current Password</label>
            <input
              type="password"
              class="form-control"
              id="currentPassword"
              v-model="pass.currentPassword"
            />
          </div>
          <div class="form-group">
            <label for="newPassword">
              New Password
              <small>(at least 8 characters)</small>
            </label>
            <input type="password" class="form-control" id="newPassword" v-model="pass.newPassword" />
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input
              type="password"
              class="form-control"
              id="confirmPassword"
              v-model="pass.confirmPassword"
            />
          </div>
          <div class="form-group">
            <button
              type="button"
              class="btn btn-success"
              id="btnsubmit"
              @click="submitChange"
            >Submit</button>
          </div>
        </form>
      </div>
    </modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      pass: {
        currentPassword: "",
        newPassword: "",
        confirmPassword: ""
      }
    };
  },
  methods: {
    submitChange() {
      document.getElementById("btnsubmit").disabled = true;
      $.ajax({
        type: "POST",
        url: "/api/changePassword",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + this.getCookie("token")
        },
        data: this.pass
      }).done(resp => {
        alert(resp.msg);
        document.getElementById("btnsubmit").disabled = false;
        if (resp.msg === "Password successfully changed") {
          this.$modal.hide("changePass");
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
  }
};
</script>
