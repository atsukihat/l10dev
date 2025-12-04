<template>
  <div class="advertisement">
    <a :href="props.href" target="_blank" rel="noopener noreferrer">
      <img :src="ad" :alt="props.alt" class="ad-image" :style="{ maxWidth: imageMaxWidth }" />
    </a>
  </div>
</template>

<script setup>
  import { ref, watch, onMounted } from "vue";
  import { useDisplay } from "vuetify";
  import ad from "../assets/img/hirodaiken_content.png";

  const { xs, sm, md, lg, xl, xxl } = useDisplay();
  const imageMaxWidth = ref("");

  const setImageMaxWidth = () => {
    if (xxl.value) {
      imageMaxWidth.value = "420px";
    } else if (xl.value) {
      imageMaxWidth.value = "390px";
    } else if (lg.value) {
      imageMaxWidth.value = "360px";
    } else if (md.value) {
      imageMaxWidth.value = "330px";
    } else if (sm.value) {
      imageMaxWidth.value = "300px";
    } else if (xs.value) {
      imageMaxWidth.value = "260px";
    } else {
      imageMaxWidth.value = "230px";
    }
  };

  onMounted(setImageMaxWidth);
  watch([xs, sm, md, lg, xl, xxl], setImageMaxWidth);

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
    height: auto;
    display: block;
    border-radius: 6px;
  }
</style>
