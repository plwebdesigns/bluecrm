<template>
  <div class="pt-3">
    <div class="row">
      <div class="col" v-for="(qrt,index) in breakdown" :key="index">
        <!-- <h3 class="font-fredricka bg-blue-realty">{{index}}</h3> -->
        <table class="table table-sm">
          <caption
            class="font-fredricka bg-blue-realty p-0 text-center"
            style=" caption-side: top; font-size: 24px; color: black"
          >{{index}}</caption>
          <tr>
            <td>SALES VOLUME</td>
            <td>{{qrt.volume.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
          <tr>
            <td>SELLERS</td>
            <td>{{qrt.sellers}}</td>
          </tr>
          <tr>
            <td>BUYERS</td>
            <td>{{qrt.buyers}}</td>
          </tr>
          <tr>
            <td>RENTALS</td>
            <td>{{qrt.rentals}}</td>
          </tr>
          <tr>
            <td>REFERRALS</td>
            <td>{{qrt.referrals}}</td>
          </tr>
          <tr>
            <td>UNITS SOLD</td>
            <td>{{qrt.units}}</td>
          </tr>
          <tr>
            <td>TRANS FEES</td>
            <td>{{qrt.trans.toLocaleString('en-us', numberFormat)}}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.getBreakdowns();
  },
  props: ["production_yr"],
  data() {
    return {
      numberFormat: {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2,
        style: "currency",
        currency: "USD"
      },
      breakdown: [
        {
          volume: 0,
          units: 0,
          buyers: 0,
          sellers: 0,
          rentals: 0,
          referrals: 0,
          trans: 0
        }
      ]
    };
  },
  methods: {
    getBreakdowns: function() {
      let token = this.getCookie("token");
      let req = {
        method: "GET",
        url: "/api/quarter-breakdown",
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token
        },
        params: {
          production_year: this.$props.production_yr
        }
      };

      axios(req).then(resp => {
        this.breakdown = resp.data.breakdown;
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
  }
};
</script>

<style scoped>
</style>
