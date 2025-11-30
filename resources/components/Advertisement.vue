<template>
  <div class="advertisement">
    <img :src="ad" alt="hirodaiken advertisement" class="ad-image" />
  </div>
</template>

<script setup>
  import ad from "../assets/img/hirodaiken_ad.png";
</script>

<style scoped>
  .advertisement {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px 0;
  }
  .ad-image {
    max-width: 400px;
    height: auto;
    display: block;
    border-radius: 6px;
  }
</style>
