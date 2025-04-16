<script setup>
  import { ref } from "vue";
  import axios from "axios";
  import CommonAlert from "../components/shared/CommonAlert.vue";

  const email = ref("");
  const message = ref("");
  const type = ref("");

  const requestResetLink = async () => {
    try {
      axios.post("/api/password/reset-request", { email: email.value });
      message.value = "パスワード再設定リンクを送信しました。";
      type.value = "success";
    } catch (error) {
      message.value = "エラーが発生しました。メールアドレスを確認してください。";
      type.value = "error";
    }
    setTimeout(() => {
      message.value = "";
      type.value = "";
    }, 5000);
  };
</script>
<template>
  <v-container>
    <h1>パスワード再設定リクエスト</h1>
    <v-form>
      <v-text-field v-model="email" label="メールアドレス" type="email" required></v-text-field>
      <v-btn @click="requestResetLink">送信</v-btn>
    </v-form>
    <common-alert
      v-if="message"
      :message="message"
      :type="type"
      unique-key="password-reset-request-alert"
    ></common-alert>
  </v-container>
</template>
