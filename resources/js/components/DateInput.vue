<template>
  <input
    class="form-control-sm form-control"
    v-model="displayValue"
    @focus="isInputActive = true"
    @blur="isInputActive = false"
    type="text"
    disabled
  />
</template>

<script>
export default {
  props: ["value"],
  data() {
    return {
      isInputActive: false
    };
  },
  computed: {
    displayValue: {
      get: function() {
        let val = this.$props.value;

        if (this.isInputActive) {
          return val.toString();
        } else {
          return new Date(val).toLocaleDateString();
        }
      },
      set: function(modifiedVal) {
        this.$emit("input", modifiedVal);
      }
    }
  }
};
</script>

<style scoped>
</style>
