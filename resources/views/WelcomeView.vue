<script setup>
  import { onMounted } from "vue";
  import Welcome from "../components/Welcome.vue";
  import SearchClasses from "../components/SearchClasses.vue";
  import FontChangeTitle from "../components/shared/FontChangeTitle.vue";

  onMounted(() => {
    const container = document.getElementById("admax-container"); // 追加するターゲットの要素
    if (container) {
      // 1. `script` タグを作成
      const script = document.createElement("script");
      script.src = "https://adm.shinobi.jp/s/a275cef6960a6f78931b7f2f1dae41c4"; // 忍者AdmaxのスクリプトURL
      container.appendChild(script);
    }
  });
</script>

<template>
  <v-container class="pb-8 bg-primary" fluid>
    <FontChangeTitle> 大学生による授業評価を、オープンに。 </FontChangeTitle>
  </v-container>
  <SearchClasses></SearchClasses>

  <!-- admax 広告の表示エリア -->
  <div id="admax-container"></div>

  <Welcome></Welcome>
</template>
