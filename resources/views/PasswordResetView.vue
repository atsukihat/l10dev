<script setup>
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import CommonAlert from "../components/shared/CommonAlert.vue";
  import axios from "axios";

  const route = useRoute();
  const router = useRouter();

  const token = route.params.token || ""; // リンクからトークンを取得
  const email = route.query.email || ""; // リンクからメールアドレスを取得
  const password = ref("");
  const passwordConfirmation = ref("");
  const message = ref("");
  const type = ref("");

  const resetPassword = async () => {
    message.value = "";
    type.value = "";
    console.log(email.value);
    try {
      const response = await axios.post("/api/password/reset", {
        token,
        email,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      });

      if (response.status === 200) {
        message.value = "パスワードが再設定されました。";
        type.value = "success";
        setTimeout(() => router.push("/login"), 5000);
      }
    } catch (error) {
      if (error.response && error.response.data && error.response.data.message) {
        message.value = error.response.data.message;
      } else if (error.response && error.response.status) {
        message.value = `エラーが発生しました。ステータスコード: ${error.response.status}`;
      } else if (error.message) {
        message.value = error.message;
      } else {
        message.value = "エラーが発生しました。入力内容を確認してください。";
      }
      type.value = "error";
      console.error(error);
    }
  };
</script>

<template>
  <v-container>
    <h1>パスワード再設定</h1>
    <v-form>
      <v-text-field v-model="email" label="メールアドレス" type="email" readonly disabled required></v-text-field>
      <v-text-field v-model="password" label="新しいパスワード" type="password" required></v-text-field>
      <v-text-field v-model="passwordConfirmation" label="パスワード確認" type="password" required></v-text-field>
      <v-btn @click="resetPassword">パスワードを再設定</v-btn>
    </v-form>
    <common-alert v-if="message" :message="message" :type="type" unique-key="password-reset-alert"></common-alert>
  </v-container>
</template>
