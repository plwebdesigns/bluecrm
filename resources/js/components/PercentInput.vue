<template>
  <input
    class="form-control"
    v-model="displayValue"
    @blur="isInputActive = false"
    @focus="isInputActive = true"
    :disabled="isDisabled"
    placeholder="0%"
  />
</template>

<script>
export default {
  props: ["value", "disabled"],
  data() {
    return {
      isInputActive: false,
      isDisabled: this.$props.disabled
    };
  },
  computed: {
    displayValue: {
      get: function() {
        let val = this.$props.value;

        if (this.isInputActive) {
          return val.toString();
        } else {
          if (val <= 1 && val > 0) {
            return (val * 100).toString() + "%";
          } else if (val > 1) {
            return val.toString() + "%";
          } else {
            return "";
          }
        }
      },
      set: function(modifiedValue) {
        this.$emit("input", modifiedValue);
      }
    }
  }
};
</script>

<style scoped>
</style>
