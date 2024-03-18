<template>
  <div v-if="valuee">
    <Loader/>
  </div>
  <div v-else>
  <div class="example">
    <div>
      <div>
        <h3 style="color: grey; display: flex; align-items: center; margin-left: -15%; justify-content: center;">
          Bar Chart
        </h3>
      </div>
      <br>
    </div>
    <div class="filterDate">
      <button id="ascending" class="btnUserSort" @click="onSortChangeScore(1)"
        style="background-color: rgb(220, 239, 247);">
        <span>Monthly Based
          <v-icon>mdi-calendar-month</v-icon>
        </span>
      </button>
      <button id="descending" class="btnUserSort" @click="onSortChangeScore(2)">
        Weekly Based
        <v-icon>mdi-calendar-week-outline</v-icon>
      </button>
    </div>
    <apexcharts max-width="1000" height="350" type="bar" :options="chartOptions" :series="series" ref="chart"></apexcharts>
  </div></div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import Loader from '../views/Loading.vue'
export default {
  name: 'bar-Graph',
  components: {
    apexcharts: VueApexCharts,Loader,
  },
  data: function () {
    return {
      valuee: false,

      chartOptions: {
        chart: {
          // To Disable animation of the graph
          // animations: {
          //   enabled: false,
          // },
          id: 'basic-bar'
        },
        xaxis: {
          categories: []
        }
      },
      series: [{
        name: 'Joined Users',
        data: []
      }]
    }
  },
  mounted(){
    this.valuee = true;
this.onSortChangeScore(1)
// this.valuee = false;
setTimeout(() => this.valuee = false, 100);


  },
  methods: {
    onSortChangeScore(sortOrder) {
      if (sortOrder === 1) {
        // this.valuee=true

    // this.valuee = true;

        document.getElementById('ascending').style.backgroundColor = "#dceff7";
        document.getElementById('descending').style.backgroundColor = "white";

      
        console.log("ss")
        console.log(this.chartOptions.xaxis.categories);
        // Monthly Based Data
        this.chartOptions.xaxis.categories = ["January", 'February', "March", "April", "may", "June", "July","August", "September", "October", "November" , "December"]
        console.log(this.chartOptions.xaxis.categories);
        this.series[0].data = [30, 40, 45, 50, 49, 60, 70 , 32,65,89,99,144]
        console.log(this.series[0].data);

      } else if (sortOrder === 2) {
        document.getElementById('descending').style.backgroundColor = "#dceff7";
        document.getElementById('ascending').style.backgroundColor = "white";
        // Weekly Based Data
        this.chartOptions.xaxis.categories = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
        console.log(this.chartOptions.xaxis.categories);
        this.series[0].data = [30, 35, 40, 45, 50, 55, 60]
        console.log(this.series[0].data);
      }

      // update chart with new data values
      this.$refs.chart.updateSeries(this.series)
      this.$refs.chart.updateOptions(this.chartOptions)
    },
  },
}
</script>
<style>


.btnUserSort {
    
    display: inline-block;
    font: inherit;
    color: #516083;
    border: 2px solid #e7e7e7 !important;
    background: white;
    padding: 0;
    cursor: pointer;
    width: -moz-fit-content;
    width: fit-content;
    width: 70px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: x-small;
  }
  
  .filterDate{
      margin-left:2%
  }
  .apexcharts-menu-icon{
      display:none;
  }
</style>