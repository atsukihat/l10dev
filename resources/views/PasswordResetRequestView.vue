<script setup>
  import { ref } from "vue";
  import axios from "axios";
  import CommonAlert from "../components/shared/CommonAlert.vue";
  import Loading from "../components/Loading.vue";

  const email = ref("");
  const message = ref("");
  const type = ref("");
  const isLoading = ref(false);
  const isSuccess = ref(false); // ✅ 成功状態を追加

  const requestResetLink = async () => {
    message.value = "";
    type.value = "error";
    isLoading.value = true;

    try {
      const response = await axios.post("/api/password/reset-request", { email: email.value });
      if (response.status === 200) {
        isSuccess.value = true; // ✅ 成功表示へ切り替え
      }
    } catch (error) {
      if (error.response?.data?.message) {
        message.value = error.response.data.message;
      } else {
        message.value = "エラーが発生しました。入力内容を確認してください。";
      }
      type.value = "error";
      console.error(error);
    } finally {
      isLoading.value = false;
    }
  };
</script>

<template>
  <v-container>
    <h1>パスワード再設定リクエスト</h1>

    <!-- ✅ ローディング中表示 -->
    <loading v-if="isLoading" />

    <!-- ✅ メール送信完了メッセージ -->
    <div v-else-if="isSuccess" class="text-center my-4">
      <v-icon size="64" color="primary">mdi-email-check-outline</v-icon>
      <p class="text-h6 mt-2">メールを送信しました</p>
      <p>パスワード再設定用のリンクを記載したメールを送信しました。<br />ご確認のうえ、手続きを完了してください。</p>
    </div>

    <!-- ✅ 入力フォーム -->
    <v-form v-else>
      <v-text-field v-model="email" label="メールアドレス" type="email" required></v-text-field>
      <v-btn :disabled="isLoading" @click="requestResetLink">送信</v-btn>
    </v-form>

    <!-- ✅ アラートメッセージ -->
    <common-alert v-if="message" :message="message" :type="type" unique-key="password-reset-request-alert" />
  </v-container>
</template>
