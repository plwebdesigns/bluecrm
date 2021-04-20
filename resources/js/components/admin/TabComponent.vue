<template>
  <div class="container-fluid">
    <ul class="nav nav-tabs">
      <li class="nav-item" v-for="tab in tabs" :key="tab" v-on:click="currentTab = tab">
        <a
          :id="tab"
          class="nav-link"
          href="#/admin"
          :class="{ active: currentTab === tab }"
        >{{(tab !== 'Agent Production') ? tab : 'Profit Per Agent'}}</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" :href="'/add_sale?api_token=' + token">Add Sale</a>
      </li>
    </ul>
    <component v-bind:is="currentTabComponent"></component>
  </div>
</template>

<script>
import TabOptions from "./OptionsComponent";
import TabAgentRoster from "./AgentControlComponent";
import TabMembershipDues from "./MembershipComponent";

import VueLoading from "vuejs-loading-plugin";

Vue.use(VueLoading);

export default {
  components: {
    TabOptions,
      TabAgentRoster,
      TabMembershipDues
  },
  mounted: function () {
    this.token = this.getCookie('token');
  },
  data() {
    return {
      currentTab: "Quarterly",
      tabs: [
        "Quarterly",
        "Agent Production",
        "Add Agent",
        "All Sales",
        "Leaderboard",
        "Reporting",
        "Agent Roster",
        "Options",
          "Membership Dues"
      ],
      token: ''
    };
  },
  computed: {
    currentTabComponent: function() {
      return "tab-" + this.currentTab.replace(" ", "-").toLocaleLowerCase();
    }
  }
};
</script>

<style scoped>
</style>
