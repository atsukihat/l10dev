<script setup>
  import { ref } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axios from "axios";
  import CommonAlert from "../components/shared/CommonAlert.vue";
  import Loading from "../components/Loading.vue";

  const route = useRoute();
  const router = useRouter();

  const token = route.params.token || "";
  const email = route.query.email || "";
  const password = ref("");
  const passwordConfirmation = ref("");
  const message = ref("");
  const type = ref("");
  const isLoading = ref(false);
  const isSuccess = ref(false); // ✅ 成功状態フラグ

  const resetPassword = async () => {
    message.value = "";
    type.value = "error";
    isLoading.value = true;

    try {
      const response = await axios.post("/api/password/reset", {
        token,
        email,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      });

      if (response.status === 200) {
        isSuccess.value = true; // ✅ 成功表示に切り替え
        setTimeout(() => {
          router.push("/login");
        }, 5000);
      }
    } catch (error) {
      if (error.response?.data?.message) {
        message.value = error.response.data.message;
      } else if (error.response?.status) {
        message.value = `エラーが発生しました。ステータスコード: ${error.response.status}`;
      } else if (error.message) {
        message.value = error.message;
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
    <h1>パスワード再設定</h1>

    <!-- ✅ ローディング中表示 -->
    <loading v-if="isLoading" />

    <!-- ✅ 成功メッセージ表示 -->
    <div v-else-if="isSuccess" class="text-center my-4">
      <v-icon size="64" color="green">mdi-check-circle</v-icon>
      <p class="text-h6 mt-2">パスワードが再設定されました！</p>
      <p>数秒後にログインページへ移動します。</p>
    </div>

    <!-- ✅ 入力フォーム表示 -->
    <v-form v-else>
      <v-text-field
        v-model="email"
        label="メールアドレス"
        type="email"
        readonly
        disabled
        required
      />
      <v-text-field
        v-model="password"
        label="新しいパスワード"
        type="password"
        required
      />
      <v-text-field
        v-model="passwordConfirmation"
        label="パスワード確認"
        type="password"
        required
      />
      <v-btn :disabled="isLoading" @click="resetPassword">パスワードを再設定</v-btn>
    </v-form>

    <!-- ✅ アラートメッセージ -->
    <common-alert
      v-if="message"
      :message="message"
      :type="type"
      unique-key="password-reset-alert"
    />
  </v-container>
</template>
