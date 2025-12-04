<script setup>
  import Login from "../components/Login.vue";
  import BaseUI from "../components/shared/BaseUI.vue";
  import passwordImg from "../assets/img/my_password_re_ydq7.svg";
  import readingImg from "../assets/img/undraw_reading_re_29f8.svg";
</script>

<template>
  <BaseUI class="base-ui">
    <v-row justify="center" align="end">
      <v-col cols="0" sm="0" md="3" lg="3" class="d-none d-md-block"
        ><v-img :src="readingImg" alt="img" width="300px"></v-img
      ></v-col>
      <v-col cols="12" sm="12" md="6" lg="6"><Login></Login></v-col>
      <v-col cols="0" sm="0" md="3" lg="3" class="d-none d-md-block"
        ><v-img :src="passwordImg" alt="img" width="300px"></v-img
      ></v-col>
    </v-row>
  </BaseUI>
</template>

<style></style>
