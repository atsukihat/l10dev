<script setup>
  import { ref } from "vue";
  import axios from "axios";
  import { useRouter } from "vue-router";
  import { useStore } from "vuex";

  const router = useRouter();
  const store = useStore();
  const userEmail = ref("");
  const password = ref("");
  const isPasswordVisible = ref(false);

  const errorMessage = ref(""); // エラーメッセージ用の変数
  const visible = ref(false);

  const clickButton = async () => {
    // const store = useStore()
    console.log("login button has been clicked");
    // console.log("クリックされたで2");
    // const res = await axios.get("/register");
    // const res = await axios.get("/api/hello");
    // console.log(res);

    const data = {
      userEmail: userEmail.value,
      password: password.value
    };
    console.log(data);

    await axios
      .post("/api/login", data)
      .then((response) => {
        // ちゃんと送信できたか確認用
        console.log(response.data.success);
        if (response.data.success) {
          data["id"] = response.data.id;
          data["role"] = response.data.role;
          // console.log(response)
          // console.log(response.data.role)
          // console.log(data['role'])
          console.log(data);
          store.dispatch("login", data);
          router.push({ name: "profile" });
        } else {
          //ログインに失敗したときのエラーメッセージ
          errorMessage.value = "メールアドレスまたはパスワードを確認してください。";
        }
        // console.log("OK");
        // axios.get("/profile")
      })
      .catch((err) => {
        console.log(err);
        // console.log("×");
      });
  };
</script>

<template>
  <v-row class="auth-wrapper">
    <v-col cols="12  d-flex align-center justify-center">
      <v-card class="auth-card pa-4 pt-7 mt-15 mb-15" max-width="448">
        <v-row justify="center">
          <v-col cols="12">
            <v-card-text class="pt-2">
              <h5 class="text-h5 font-weight-semibold mb-4 text-center">かえでにようこそ!</h5>
              <p class="mb-0 text-center">メールアドレスとパスワードでログインしてください</p>
            </v-card-text>
          </v-col>

          <v-col cols="12">
            <v-card-text>
              <v-form justify="center">
                <v-row>
                  <!-- email -->
                  <v-col cols="12">
                    <v-text-field v-model="userEmail" variant="outlined" clearable label="Email" type="email" />
                  </v-col>

                  <!-- password -->
                  <v-col cols="12">
                    <v-text-field
                      v-model="password"
                      variant="outlined"
                      clearable=""
                      label="Password"
                      :type="isPasswordVisible ? 'text' : 'password'"
                      :append-inner-icon="isPasswordVisible ? 'mdi-eye-off' : 'mdi-eye-outline'"
                      @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    />
                  </v-col>

                  <v-col cols="12">
                    <!-- login button -->
                    <v-btn color="primary" block @click="clickButton">
                      <p class="text-white">ログイン</p>
                    </v-btn>
                  </v-col>

                  <!-- login error message -->
                  <v-col cols="12" class="text-center text-base error-message">
                    {{ errorMessage }}
                  </v-col>
                </v-row>
                <v-row justify="start">
                  <!-- create account -->
                  <!-- <v-col
                    cols="12" sm="6" md="" lg="" xl=""
                    class="text-center text-base"
                  >
                    <span>初めてご利用になる方</span>
                  </v-col> -->
                  <v-col
                    cols="12"
                    sm="12"
                    md=""
                    lg=""
                    xl=""
                    justify="center"
                    class="text-center text-base text-primary"
                  >
                    <RouterLink to="/register"> アカウントの新規作成 </RouterLink>
                  </v-col>
                </v-row>
                <v-row justify="start">
                  <v-col
                    cols="12"
                    sm="12"
                    md=""
                    lg=""
                    xl=""
                    justify="center"
                    class="text-center text-base text-primary"
                  >
                    <RouterLink :to="{ name: 'password-reset-request' }"> パスワードを忘れた方はこちら </RouterLink>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
          </v-col>
        </v-row>
      </v-card>
    </v-col>
  </v-row>
</template>
<style scoped>
  .error-message {
    color: red;
    font-weight: bold;
  }
</style>
