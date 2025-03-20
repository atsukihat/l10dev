<script setup>
  import { onMounted, ref } from "vue";

  const ads = ref(null);

  onMounted(() => {
    if (!ads.value) return; // 安全チェック

    // 1. iframe を作成
    const iframe = document.createElement("iframe");
    iframe.style.width = "100%";
    iframe.style.height = "250px"; // 高さは広告サイズに応じて調整
    iframe.style.border = "none";

    // 2. iframe を DOM に追加
    ads.value.appendChild(iframe);

    // 3. iframe 内の HTML を生成
    const html = `<body>
        <script src="https://adm.shinobi.jp/s/a275cef6960a6f78931b7f2f1dae41c4"><\/script>
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
  .ad {
    width: 300px; /* 忍者Admax の広告サイズに調整 */
    height: 250px;
  }
</style>
