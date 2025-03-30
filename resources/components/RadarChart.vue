<script setup>
  import { ref, onMounted } from "vue";

  const props = defineProps(["radarChartData"]);

  // Chart.js
  const loadChartJS = () => {
    return new Promise((resolve, reject) => {
      const scriptChartJS = document.createElement("script");
      scriptChartJS.src = "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.umd.js";
      scriptChartJS.defer = true;
      document.head.appendChild(scriptChartJS);

      scriptChartJS.onload = resolve;
      scriptChartJS.onerror = reject;
    });
  };

  const radarChart = ref(null);

  const chartData = {
    labels: ["単位取得のしやすさ", "面白さ", "スキルが身につく"],
    datasets: [
      {
        label: "カテゴリ別5段階評価",
        data: [props.radarChartData.creditLevel, props.radarChartData.interestLevel, props.radarChartData.skillLevel],
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 2
      }
    ]
  };

  onMounted(async () => {
    await loadChartJS();

    // Wait for the script to be executed
    await new Promise((resolve) => {
      const checkExist = setInterval(() => {
        if (typeof Chart !== "undefined") {
          clearInterval(checkExist);
          resolve();
        }
      }, 100);
    });

    // Create Radar Chart
    const context = radarChart.value.getContext("2d");
    new Chart(context, {
      type: "radar",
      data: chartData,
      options: {
        scales: {
          r: {
            beginAtZero: true,
            min: 0,
            max: 5, // Adjust the max value as needed
            stepSize: 1 // Adjust the step size as needed
          }
        },
        maintainAspectRatio: false,
        responsive: true
      }
    });
  });
</script>

<template>
  <div class="wrap-chart">
    <div class="chart-container">
      <canvas ref="radarChart"></canvas>
    </div>
  </div>
</template>

<style>
  /* css */
  .wrap-chart {
    width: 100%;
  } 
</style>
