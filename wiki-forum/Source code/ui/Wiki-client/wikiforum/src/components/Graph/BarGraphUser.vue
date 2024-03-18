<template>
    <div>
      <apexchart
        :options="chartOptions"
        :series="chartSeries"
        type="bar"
        height="350"
      />
    </div>
  </template>
  
  <script>
  export default {
    name: "LineChart",
    data() {
      return {
        chartOptions: {
          chart: {
            id: "line-chart",
            animations: {
              enabled: true,
              easing: "linear",
              dynamicAnimation: {
                speed: 1000,
              },
            },
          },
          xaxis: {
            categories: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
          },
        },
        chartSeries: [
          {
            name: "Questions",
            data: [30, 40, 35, 50, 49, 60, 70 ],
          },
        ],
      };
    },
  };
  </script>
  <style>
  /* .apexcharts-toolbar{
    display:none;
  } */
  .apexcharts-toolbar{
    display:none !important;
  }
  .apexcharts-menu-icon{
    display:none;
}
  </style>