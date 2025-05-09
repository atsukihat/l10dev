<script setup>
  import { ref, computed } from "vue";
  import { useRoute, useRouter } from "vue-router";
  import axios from "axios";
  import useVuelidate from "@vuelidate/core";
  import { required, minLength, maxLength, sameAs } from "@vuelidate/validators";
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
  const visible = ref(false);
  const isLoading = ref(false);
  const isSuccess = ref(false);

  // バリデーションルール
  const rules = computed(() => ({
    password: {
      required,
      minLength: minLength(8),
      maxLength: maxLength(32)
    },
    passwordConfirmation: {
      required,
      sameAsPassword: sameAs(password)
    }
  }));

  const v$ = useVuelidate(rules, {
    password,
    passwordConfirmation
  });

  const resetPassword = async () => {
    const isValid = await v$.value?.$validate();
    if (!isValid) {
      message.value = "入力内容に誤りがあります。";
      type.value = "error";
      return;
    }

    message.value = "";
    type.value = "error";
    isLoading.value = true;

    try {
      const response = await axios.post("/api/password/reset", {
        token,
        email,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      });

      if (response.status === 200) {
        isSuccess.value = true;
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
  <v-row>
    <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="d-flex flex-column align-center">
      <loading v-if="isLoading" />

      <v-card v-else-if="isSuccess" class="auth-card pa-4 pt-7 mb-15 text-center" max-width="500">
        <v-icon size="64" color="green">mdi-check-circle</v-icon>
        <v-card-text class="pt-2">
          <h5 class="text-h5 font-weight-semibold mb-4">パスワードが再設定されました！</h5>
          <p>数秒後にログインページへ移動します。</p>
        </v-card-text>
      </v-card>

      <v-card v-else class="auth-card pa-4 pt-7 mb-15" max-width="500">
        <v-card-text class="pt-2">
          <h5 class="text-h5 font-weight-semibold mb-4 text-center">パスワード再設定</h5>
        </v-card-text>
        <v-form @submit.prevent="resetPassword">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="email"
                label="メールアドレス"
                type="email"
                readonly
                disabled
                required
                class="mb-4"
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="password"
                :error-messages="v$.password?.$error ? ['8字以上32字以下の, 有効なパスワードを入力してください. '] : []"
                :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                :type="visible ? 'text' : 'password'"
                label="新しいパスワード(8~32文字)"
                name="password"
                clearable
                variant="outlined"
                class="mb-4"
                @click:append-inner="visible = !visible"
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="passwordConfirmation"
                :error-messages="
                  v$.passwordConfirmation?.$error ? ['入力されたパスワードが確認用パスワードと一致しません. '] : []
                "
                :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                :type="visible ? 'text' : 'password'"
                label="パスワード確認"
                name="passwordConfirmation"
                clearable
                variant="outlined"
                class="mb-6"
                @click:append-inner="visible = !visible"
              />
            </v-col>
            <v-col cols="12" class="text-center">
              <v-btn type="submit" :disabled="isLoading" color="primary"> 登録する </v-btn>
            </v-col>
          </v-row>
        </v-form>
      </v-card>

      <common-alert v-if="message" :message="message" :type="type" unique-key="password-reset-alert" class="mt-4" />
    </v-col>
  </v-row>
</template>
