<template>
  <div>
    <modal
      name="errorModal"
      @before-open="beforeOpen"
      height="auto"
      width="800px"
      :scrollable="true"
      :draggable="false"
    >
      <div class="container m-3 p-2">
        <h5>There was a problem with your submission</h5>
        <ul>
          <li class="text-danger" v-for="(e, index) in errors" :key="index">{{e}}</li>
        </ul>
        <button class="btn btn-primary" @click="hide">Close</button>
      </div>
    </modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      errors: []
    };
  },
  methods: {
    beforeOpen: function(event) {
      this.errors = event.params.data;
    },
    hide: function() {
      this.$modal.hide("errorModal");
    }
  }
};
</script>
