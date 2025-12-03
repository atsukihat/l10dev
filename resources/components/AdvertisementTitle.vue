<template>
  <div class="advertisement">
    <a :href="props.href" target="_blank" rel="noopener noreferrer">
      <img :src="ad" :alt="props.alt" class="ad-image" />
    </a>
  </div>
</template>

<script setup>
  import ad from "../assets/img/hirodaiken_title.png";
  const props = defineProps({
    alt: {
      type: String,
      default: "広大研"
    },
    href: {
      type: String,
      default: "https://hirodaiken.jp/"
    }
  });
</script>

<style scoped>
  .advertisement {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px 0;
  }
  .ad-image {
    max-width: 290px;
    height: auto;
    display: block;
    border-radius: 6px;
  }
</style>
