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
        <option v-for="(date, index) in all_dates" :value="date" :key="index">{{date}}</option>
    </select>
  </div>
</template>

<script>
export default {
    mounted() {
        this.calcNumberYears();
    },
    data() {
    return {
      production_year: new Date().getFullYear().toString(),
      all_dates: []
    };
  },
    methods:{
      calcNumberYears(){
          let arr = [];
          let d = new Date().getFullYear();
          let d_count = 2019; //First year of CRM being utilized
          let n = d - d_count;

          for (let i = 0; i <= n; i++){
              arr.push(d_count + i);
          }

          this.all_dates = arr;
      }
    }
};
</script>

<style lang="css" scoped>
</style>
