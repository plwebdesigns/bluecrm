<template>
  <div>
    <form>
          <input
            class="form-control"
            type="text"
            name="search-term"
            v-model="search.term"
            @keyup="update"
            @keypress.enter.prevent="update"
            placeholder="Search Here"
          />
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: {
        term: "",
        by: ""
      }
    };
  },
  methods: {
    update: _.debounce(function(e) {
      this.$emit("search", this.search);
    }, 500)
  }
};
</script>
