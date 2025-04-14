<script setup lang="ts">
  import { ref, computed, onMounted, onUnmounted, nextTick } from "vue";
  import { Bar } from "vue-chartjs";
  import Loading from "./Loading.vue";
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ChartOptions,
    ChartData
  } from "chart.js";

  // Chart.js モジュール登録
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

  // Props 定義
  interface Props {
    barGraphData: Record<string, number>;
    chartTitle: string;
  }
  const props = defineProps<Props>();

  // データ整形
  const extractKeyAndValue = (jsonData: Record<string, number>) => {
    const keys = Object.keys(jsonData);
    const values = Object.values(jsonData);
    return { keys, values };
  };

  const { keys: labels, values: data } = extractKeyAndValue(props.barGraphData);

  // Y軸の最大値調整
  const getMaxValueResponse = (arr: number[]) => {
    const thresholds = [10, 25, 50, 100];
    const maxValue = Math.max(...arr);
    return thresholds.find((t) => maxValue < t) ?? maxValue;
  };
  const maxValue = getMaxValueResponse(data);

  // チャートデータ
  const chartData = computed<ChartData<"bar">>(() => ({
    labels,
    datasets: [
      {
        label: props.chartTitle,
        data,
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 2
      }
    ]
  }));

  // チャートオプション
  const chartOptions = computed<ChartOptions<"bar">>(() => ({
    responsive: false,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        min: 0,
        max: maxValue,
        ticks: { stepSize: 2 }
      }
    },
    animations: {
      x: { duration: 0 },
      y: { duration: 0 },
      datasets: {
        bar: {
          duration: 800,
          easing: "easeOutCubic"
        }
      }
    }
  }));

  // ウィンドウサイズ監視
  const windowWidth = ref(window.innerWidth);
  const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth;
  };
  onMounted(() => window.addEventListener("resize", updateWindowWidth));
  onUnmounted(() => window.removeEventListener("resize", updateWindowWidth));

  // チャートサイズ（Vuetifyのブレークポイントに準拠）
  const chartWidth = computed(() => {
    const w = windowWidth.value;
    if (w < 600) return 300;
    if (w < 960) return 500;
    if (w < 1264) return 600;
    if (w < 1904) return 700;
    return 800;
  });
  const chartHeight = computed(() => (chartWidth.value * 2) / 3);

  // 描画タイミング制御
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
    <Bar v-else :key="chartWidth" :data="chartData" :options="chartOptions" :width="chartWidth" :height="chartHeight" />
  </div>
</template>
