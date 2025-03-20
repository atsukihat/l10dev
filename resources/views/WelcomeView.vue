<script setup>
  import { onMounted } from "vue";
  import Welcome from "../components/Welcome.vue";
  import SearchClasses from "../components/SearchClasses.vue";
  import FontChangeTitle from "../components/shared/FontChangeTitle.vue";
  import Admax from "../components/Admax.vue";
</script>

<template>
  <v-container class="pb-8 bg-primary" fluid>
    <FontChangeTitle> 大学生による授業評価を、オープンに。 </FontChangeTitle>
  </v-container>
  <SearchClasses></SearchClasses>

  <Admax></Admax>


  <Welcome></Welcome>
</template>
