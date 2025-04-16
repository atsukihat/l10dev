<script setup>
  import { ref } from "vue";
  import axios from "axios";

  const email = ref("");
  const message = ref("");

  const requestResetLink = async () => {
    try {
      axios.post("/api/password/reset-request", { email: email.value });
      message.value = "パスワード再設定リンクを送信しました。";
    } catch (error) {
      message.value = "エラーが発生しました。メールアドレスを確認してください。";
    }
  };
</script>

<template>
  <v-container>
    <h1>パスワード再設定リクエスト</h1>
    <v-form>
      <v-text-field v-model="email" label="メールアドレス" type="email" required></v-text-field>
      <v-btn @click="requestResetLink">送信</v-btn>
    </v-form>
    <p>{{ message }}</p>
  </v-container>
</template>
