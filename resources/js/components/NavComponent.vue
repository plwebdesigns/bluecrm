<template>
  <div
    class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm"
  >
    <img class="my-0 mr-md-auto" src="images/blue-logo.png" height="95px" />
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/leaderboard">Leaderboard</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/">Production</router-link>
          </li>
            <li class="nav-item">
                    <router-link class="nav-link" to="/profile">Profile</router-link>
            </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/admin" v-if="isAdmin">Admin</router-link>
          </li>
        </ul>
      </div>
    </nav>
    <a class="btn btn-outline-primary btn-sm" href="/api/logout">Logout</a>
  </div>
</template>

<script>
export default {
  props: ["isAdmin"],
};
</script>
<style>
    a.pointer{
        cursor: pointer;
    }
</style>
