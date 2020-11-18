<template>
  <div>
    <modal @before-open="beforeOpen" name="messageModal" height="200px">
      <div class="container">
        <div class="alert">
          <h4 class="mb-5" :class="font_color">{{message}}</h4>
          <button type="button" class="btn btn-primary mt-5" @click="hide">Close</button>
        </div>
      </div>
    </modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      message: "",
      font_color: ""
    };
  },
  methods: {
    hide: function() {
      this.$modal.hide("messageModal");
    },
    beforeOpen: function(event) {
      this.message = event.params.data;
      this.font_color = event.params.style.class;
    }
  }
};
</script>
