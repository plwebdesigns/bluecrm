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
import TabChangePasswordForAgent from "./ChangePasswordForAgent";

import VueLoading from "vuejs-loading-plugin";

Vue.use(VueLoading);

export default {
  components: {
    TabOptions,
      TabAgentRoster,
      TabMembershipDues,
      TabChangePasswordForAgent
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
        "All Sales",
        "Leaderboard",
        "Reporting",
        "Agent Roster",
        "Options",
        "Add Agent",
        "Change Password For Agent"
      ],
      token: ''
    };
  },
  computed: {
    currentTabComponent: function() {
      return "tab-" + this.currentTab.replace(/\s/g, "-").toLocaleLowerCase();
    }
  }
};
</script>

<style scoped>
</style>
