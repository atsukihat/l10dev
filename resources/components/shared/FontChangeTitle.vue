<!-- Title.vue -->
<template>
  <div id="title-block">
    <p :style="{ fontSize: fontSize, fontFamily: 'Kaisei' }" class="text-white">
      <slot></slot>
    </p>
  </div>
</template>

<script setup>
  import { ref, watch, onMounted } from "vue";
  import { useDisplay } from "vuetify";

  const { xs, sm, md, lg, xl, xxl } = useDisplay();
  const fontSize = ref("");

  // text-h◯とfontの変更は同時に使用できないため，fontSizeを使用する
  const setFontSize = () => {
    if (xxl.value) {
      fontSize.value = "2.5rem";
    } else if (xl.value) {
      fontSize.value = "2.2rem";
    } else if (lg.value) {
      fontSize.value = "2rem";
    } else if (md.value) {
      fontSize.value = "1.8rem";
    } else if (sm.value) {
      fontSize.value = "1.5rem";
    } else if (xs.value) {
      fontSize.value = "1.0rem";
    } else {
      fontSize.value = "0.8rem";
    }
  };

  onMounted(setFontSize);
  watch([xs, sm, md, lg, xl, xxl], setFontSize);
</script>

<style>
  #title-block {
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>
