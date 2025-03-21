<script setup>
  import { onMounted, ref } from "vue";

  const ads = ref(null);

  onMounted(() => {
    if (!ads.value) return; // 安全チェック

    // 1. iframe を作成
    const iframe = document.createElement("iframe");
    iframe.style.width = "100%";
    iframe.style.height = "100%";
    iframe.style.border = "none";

    // 2. iframe を DOM に追加
    ads.value.appendChild(iframe);

    // 3. iframe 内の HTML を生成
    const html = `<body style="display: flex; justify-content: center; align-items: center; margin: 0;">
        <script src="https://adm.shinobi.jp/o/473c6e012db1c0e34a0f642e4d165620"><\/script>
      </body>`;

    // 4. iframe 内の document に書き込み
    const iframeDocument = iframe.contentWindow.document;
    iframeDocument.open();
    iframeDocument.write(html);
    iframeDocument.close();
  });
</script>

<template>
    <v-container class="d-flex justify-center">
        <div ref="ads" class="ad"></div>
    </v-container>
</template>

<style scoped>
  html {
    /* 子要素を水平方向の中身に配置 */
    display: flex;
    justify-content: center;
    /* 子要素を垂直方向の中身に配置 */
    align-items: center;
  }
  .ad {
    /* 子要素をの中心に配置 */
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%; /* 忍者Admax の広告サイズに調整 */
  }

  @media screen and (max-width: 768px) {
    .ad {
      width: 100%;
      height: 100%; /* スマホ版の広告サイズに調整 */
    }
  }
</style>
