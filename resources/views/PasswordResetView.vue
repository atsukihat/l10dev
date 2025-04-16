<script setup>
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axios from "axios";

  const route = useRoute();
  const router = useRouter();

  const token = route.params.token || ""; // リンクからトークンを取得
  const email = route.query.email || ""; // リンクからメールアドレスを取得
  const password = ref("");
  const passwordConfirmation = ref("");
  const message = ref("");

  const resetPassword = async () => {
    console.log(email.value);
    try {
      const response = await axios.post("/api/password/reset", {
        token,
        email,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      });

      message.value = response.data.message || "パスワードが再設定されました。";
      setTimeout(() => router.push("/login"), 2000);
    } catch (error) {
      // バックエンドからのエラー本文に message がある場合はそれを使う
      if (error.response && error.response.data && error.response.data.message) {
        message.value = error.response.data.message;
      } else {
        message.value = "エラーが発生しました。入力内容を確認してください。";
      }
      console.error(error); // ログ出力もあると安心
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
