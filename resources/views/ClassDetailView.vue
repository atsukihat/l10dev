<script setup>
  import { ref, getCurrentInstance, onBeforeMount } from "vue";
  import { useRouter } from "vue-router";
  import ClassDetail from "../components/ClassDetail.vue";
  import Loading from "../components/Loading.vue";
  import axios from "axios";
  import { useMessage } from "../components/composables/useMessage";
  import CommonAlert from "../components/shared/CommonAlert.vue";


  const router = useRouter();
  const { message, messageType, setErrorMessage } = useMessage();

  const lectureId = ref(null);
  // 後でこのコメントアウトは外す！
  const classDetailData = ref(null);
  const isLoading = ref(true);

  const getclassDetailData = async (lectureId) => {
    const data = {
      lectureId: lectureId.value
    };

    try {
      // TODO: ここはPOSTよりもGETの方が適切。余力があれば修正したいですね
      const response = await axios.post("/api/searchByLectureId", data);
      // const response = await axios.post("/api/hasLectureCode", data);
      console.log(response.data);

      return response.data;
      // その他の処理
    } catch (error) {
      if (error.response) {
        // サーバーからのエラーレスポンスがある場合
        if (error.response.status === 404) {
          router.push({
            name: "notFound"
          });
        }
      }
      // リクエストがサーバーに届かなかった場合など
      setErrorMessage("エラーが発生しました。時間をおいて再度お試しください。");
    }
  };

  onBeforeMount(async () => {
    // contextから$routeを取得する
    const { $route } = getCurrentInstance().appContext.config.globalProperties;
    //lectureIdを$routeから取得
    lectureId.value = $route.params.lectureId;
    console.log(`lecture id  is ${lectureId.value}`);

    classDetailData.value = await getclassDetailData(lectureId);

    isLoading.value = false;
  });
</script>

<template>
  <v-container v-if="isLoading">
    <Loading />
  </v-container>

  <v-container v-else-if="classDetailData">
    <ClassDetail :class-detail-data="classDetailData"></ClassDetail>
  </v-container>

  <common-alert :message="message" :type="messageType" />
</template>
