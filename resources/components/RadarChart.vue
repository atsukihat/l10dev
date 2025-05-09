<script setup lang="ts">
  import { ref, computed, onMounted, onUnmounted, nextTick } from "vue";
  import { Radar } from "vue-chartjs";
  import Loading from "./Loading.vue";
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    ChartData,
    ChartOptions
  } from "chart.js";

  // 必要なチャート要素を登録
  ChartJS.register(Title, Tooltip, Legend, RadialLinearScale, PointElement, LineElement, Filler);

  // Props 型定義
  interface RadarChartData {
    creditLevel: number;
    interestLevel: number;
    skillLevel: number;
  }

  const props = defineProps<{
    radarChartData: RadarChartData;
  }>();

  // ラベルとデータ定義
  const labels = ["単位取得のしやすさ", "面白さ", "スキルが身につく"];

  const chartData = computed<ChartData<"radar">>(() => ({
    labels,
    datasets: [
      {
        label: "カテゴリ別5段階評価",
        data: [props.radarChartData.creditLevel, props.radarChartData.interestLevel, props.radarChartData.skillLevel],
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 2
      }
    ]
  }));

  const chartOptions = computed<ChartOptions<"radar">>(() => ({
    responsive: false,
    maintainAspectRatio: false,
    scales: {
      r: {
        beginAtZero: true,
        min: 0,
        max: 5,
        ticks: {
          stepSize: 1
        }
      }
    },
    animations: {
      radius: {
        duration: 800,
        easing: "easeOutQuart"
      }
    }
  }));

  // ウィンドウ幅に応じたサイズ
  const windowWidth = ref(window.innerWidth);
  const updateWidth = () => {
    windowWidth.value = window.innerWidth;
  };
  onMounted(() => window.addEventListener("resize", updateWidth));
  onUnmounted(() => window.removeEventListener("resize", updateWidth));

  const chartWidth = computed(() => {
    const w = windowWidth.value;
    if (w < 600) return 300;
    if (w < 960) return 400;
    if (w < 1264) return 600;
    if (w < 1904) return 700;
    return 800;
  });
  const chartHeight = computed(() => (chartWidth.value * 2) / 3);

  const isChartReady = ref(false);
  onMounted(async () => {
    await nextTick();
    isChartReady.value = true;
    setTimeout(() => window.dispatchEvent(new Event("resize")), 100);
  });
</script>

<template>
  <div :style="{ width: chartWidth + 'px', height: chartHeight + 'px' }">
    <Loading v-if="!isChartReady" />
    <Radar
      v-else
      :key="chartWidth"
      :data="chartData"
      :options="chartOptions"
      :width="chartWidth"
      :height="chartHeight"
    />
  </div>
</template>
