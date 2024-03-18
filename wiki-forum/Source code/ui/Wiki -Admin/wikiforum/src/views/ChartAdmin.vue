<template>
  <div v-if="valuee">
  <Loading/>
  </div>
  <div v-else>
  <div>
    <div> <h3 style="color:grey;display: flex;    align-items: center;margin-left:-15%;
    justify-content: center;">Pie Chart</h3></div>
    <br>
    <apexchart type="pie" :options="chartOptions" :series="chartSeries"  max-width="800" height="350" />
  </div></div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'
import http from '../http-common'
import Loading from '../views/Loading.vue'
export default {
  components: {
  apexchart: VueApexCharts,Loading
},
  setup() {
    
  },
  data () {
  return {
    valuee:false,
    chartOptions: {
      labels: ['Total Questions ','Total Deleted Questions','Total Closed Questions', 'Total Answered Questions' ,'Total Unanswered Questions', 'Total Verified Questions'],
      colors: ['#F44336', '#9c27b0', '#2196F3', '#ff9800', '#FF33FF' , '#606060', ]
    },
    chartSeries: []
  }
},
beforeMount(){
this.valuee=true
this.count()
setTimeout(() => this.valuee = false, 200);

},
methods : {
  count(){
        http
      .get("/admin/pie/count")
      .then((response) => {
        console.log(response)
        
        this.Counts=response.data
        
        const dataArray = Object.values(response.data)
          
     
          this.chartSeries = dataArray
        
    })
    }
}
}
</script>