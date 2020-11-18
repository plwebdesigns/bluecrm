<template>
  <div>
    <div class="col-3">
      <div class="card" style="width: 18rem">
        <img :src="'storage/' + agent.picture_url" class="card-img-top" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProfileComponent",
  created() {
    this.getProfile();
  },
  data() {
    return {
      agent: {
        picture_url: ""
      }
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
    getProfile() {
      let token = this.getCookie("token");
      let req = {};

        req = {
          method: "GET",
          url: "/api/profile",
          headers: {
            Accept: "application/json",
            Authorization: "Bearer " + token
          }
        };

      axios(req).then(resp => {
        this.agent = resp.data.agent;
      });
    },
  }
};
</script>

<style scoped>
</style>
