import VueRouter from "vue-router";
import VModal from 'vue-js-modal';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

Vue.use(VueRouter);
Vue.use(VModal);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "nav-component",
    require("./components/NavComponent.vue").default
);
Vue.component(
    "sidebar-component",
    require("./components/SidebarComponent.vue").default
);
Vue.component(
    'search-component',
    require('./components/SearchComponent.vue').default
);
Vue.component(
    'admin-sale',
    require('./components/AdminSaleComponent.vue').default
);
Vue.component(
    'detail-sale',
    require('./components/DetailSalesComponent.vue').default
);
Vue.component(
    'error-modal',
    require('./components/admin/ErrorModalComponent.vue').default
);
Vue.component(
    'message-modal',
    require('./components/admin/MessageModal.vue').default
);
Vue.component(
    "profile-component",
    require("./components/ProfileComponent.vue").default
);

/*** Admin Tab Section */
Vue.component(
    "tab-component",
    require("./components/admin/TabComponent.vue").default
);
Vue.component(
    "tab-quarterly",
    require("./components/admin/DashComponent.vue").default
);
Vue.component(
    "tab-agent-production",
    require("./components/admin/ProfitComponent.vue").default
);
Vue.component(
    "tab-add-sale",
    require("./components/admin/AddSaleComponent.vue").default
);
Vue.component(
    "tab-leaderboard",
    require("./components/admin/LeaderComponent.vue").default
);
Vue.component(
    "tab-reporting",
    require("./components/admin/ReportComponent.vue").default
);
Vue.component(
    'tab-all-sales',
    require('./components/AllSalesComponent.vue').default
);
Vue.component(
    "tab-add-agent",
    require("./components/admin/AddAgentComponent").default
);

/*** Router View Components */
const overview = Vue.component(
    'overview-component',
    require('./components/admin/OverviewComponent.vue').default
);
const dash = Vue.component(
    "dashboard-component",
    require("./components/DashboardComponent.vue").default
);
const sales = Vue.component(
    "sales-component",
    require("./components/SalesComponent").default
);
const profile = Vue.component(
    'agent-profile-component',
    require('./components/AgentProfileComponent').default
);

const routes = [
    { path: "", component: sales },
    { path: '/leaderboard', component: dash },
    { path: '/admin', component: overview },
    { path: '/profile', component: profile}
];

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
   methods: {
       getCookie(cname) {
           let name = cname + "=";
           let decodedCookie = decodeURIComponent(document.cookie);
           let ca = decodedCookie.split(";");
           for (let i = 0; i < ca.length; i++) {
               let c = ca[i];
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
});
const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
    router
}).$mount("#app");
