<script setup>
  import Welcome from "../components/Welcome.vue";
  import SearchClasses from "../components/SearchClasses.vue";
  import FontChangeTitle from "../components/shared/FontChangeTitle.vue";
</script>

<template>
  <v-container class="pb-8 bg-primary" fluid>
    <FontChangeTitle>
      大学生による授業評価を、オープンに。
      <!-- ~広大生の学びを支える情報サイト~ -->
    </FontChangeTitle>
  </v-container>
  <SearchClasses></SearchClasses>
  <Welcome></Welcome>
</template>
