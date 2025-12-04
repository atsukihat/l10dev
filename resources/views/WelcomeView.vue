<script setup>
  import Welcome from "../components/Welcome.vue";
  import SearchClasses from "../components/SearchClasses.vue";
  import FontChangeTitle from "../components/shared/FontChangeTitle.vue";
  import AdvertisementTitle from "../components/AdvertisementTitle.vue";
  import AdvertisementContent from "../components/AdvertisementContent.vue";
</script>

<template>
  <v-container class="bg-primary pb-0" fluid>
    <FontChangeTitle> 大学生による授業評価を、オープンに。 </FontChangeTitle>
    <AdvertisementTitle></AdvertisementTitle>
  </v-container>
  <SearchClasses></SearchClasses>

  <AdvertisementContent></AdvertisementContent>

  <Welcome></Welcome>
</template>
