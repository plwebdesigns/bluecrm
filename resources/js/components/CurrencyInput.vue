<template>
  <input
    class="form-control"
    type="text"
    v-model="displayValue"
    @focus="isInputActive = true"
    @blur="isInputActive = false"
    :disabled="isDisabled"
    placeholder="$0"
  />
</template>

<script>
export default {
  props: ["value", "disabled"],
  data() {
    return {
      isInputActive: false,
      isDisabled: this.$props.disabled,
      curVal: this.$props.value
    };
  },
  methods: {},
  computed: {
    displayValue: {
      get: function() {
        let val = this.$props.value;

        if (this.isInputActive) {
          return val.toString();
        } else if (val == "") {
          return "";
        } else {
          return Number(val).toLocaleString("en-us", {
            maximumFractionDigits: 2,
            minimumFractionDigits: 2,
            style: "currency",
            currency: "USD"
          });
        }
      },

      set: function(modifiedValue) {
        let newValue = parseFloat(modifiedValue.replace(/[^\d\.]/g, ""));

        if (isNaN(newValue)) {
          newValue = 0;
        }
        this.$emit("input", newValue);
      }
    }
  }
};
</script>

<style>
</style>
