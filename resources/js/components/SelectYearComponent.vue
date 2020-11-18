<template>
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <label class="input-group-text bg-blue-realty text-white">Production Year</label>
    </div>
    <select
      class="custom-select"
      id="production_year"
      v-model="production_year"
      v-on:change="$emit('production_year', production_year)"
    >
      <option value="2019">2019</option>
      <option selected :value="current_year">{{current_year}}</option>
    </select>
  </div>
</template>

<script>
export default {
  data() {
    return {
      production_year: "2020",
      current_year: new Date().getFullYear()
    };
  }
};
</script>

<style lang="css" scoped>
</style>
