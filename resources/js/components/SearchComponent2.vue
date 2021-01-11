<template>
  <div>
    <form>
          <input
            class="form-control"
            type="text"
            name="search-term2"
            v-model="search.term"
            @keyup="update"
            @keypress.enter.prevent="update"
            placeholder="Search Adresses"
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
        search_by: "address"
      }
    };
  },
  methods: {
    update: _.debounce(function(e) {
      this.$emit("search_clients", this.search);
    }, 500)
  }
};
</script>
