<script setup>
  import { ref } from "vue";
  import axios from "axios";
  import CommonAlert from "../components/shared/CommonAlert.vue";
  import Loading from "../components/Loading.vue";

  const email = ref("");
  const message = ref("");
  const type = ref("");
  const isLoading = ref(false);
  const isSuccess = ref(false);

  const requestResetLink = async () => {
    message.value = "";
    type.value = "error";
    isLoading.value = true;

    try {
      const response = await axios.post("/api/password/reset-request", { email: email.value });
      if (response.status === 200) {
        isSuccess.value = true; // 成功表示へ切り替え
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
  <v-row>
    <v-col
      cols="12"
      sm="12"
      md="12"
      lg="12"
      xl="12"
      class="d-flex justify-center align-center"
      style="min-height: 80vh"
    >
      <loading v-if="isLoading" />

      <!-- メール送信完了メッセージ -->
      <v-card v-else-if="isSuccess" class="auth-card pa-4 pt-7 mb-15 text-center" max-width="500">
        <v-icon size="64" color="primary">mdi-email-check-outline</v-icon>
        <v-card-text class="pt-2">
          <h5 class="text-h5 font-weight-semibold mb-4">メールを送信しました</h5>
          <p>
            パスワード再設定用のリンクを記載したメールを送信しました。<br />
            ご確認のうえ、手続きを完了してください。
          </p>
        </v-card-text>
      </v-card>

      <!-- 入力フォーム -->
      <v-card v-else class="auth-card pa-4 pt-7 mb-15" max-width="500">
        <v-card-text class="pt-2">
          <h5 class="text-h5 font-weight-semibold mb-4 text-center">パスワード再設定リクエスト</h5>
        </v-card-text>

        <v-form @submit.prevent="requestResetLink">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="email"
                label="メールアドレス"
                type="email"
                required
                variant="outlined"
                class="mb-4"
              />
            </v-col>
            <v-col cols="12" class="text-center">
              <v-btn type="submit" :disabled="isLoading" color="primary"> 送信 </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-card>

      <common-alert
        v-if="message"
        :message="message"
        :type="type"
        unique-key="password-reset-request-alert"
        class="mt-4"
      />
    </v-col>
  </v-row>
</template>
