<script setup>
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axios from "axios";

  const route = useRoute();
  const router = useRouter();

  const token = route.query.token || ""; // リンクからトークンを取得
  const email = route.query.email || "";  // リンクからメールアドレスを取得
  const password = ref("");
  const passwordConfirmation = ref("");
  const message = ref("");

  const resetPassword = async () => {
    try {
      await axios.post("/api/password/reset", {
        token,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      });
      message.value = "パスワードが再設定されました。ログインページに移動します。";
      setTimeout(() => router.push("/login"), 2000);
    } catch (error) {
      message.value = "エラーが発生しました。入力内容を確認してください。";
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
    <p>{{ message }}</p>
  </v-container>
</template>
