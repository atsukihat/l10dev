<script setup>
  import { useRoute, onBeforeRouteUpdate } from "vue-router";
  import { ref, onBeforeMount } from "vue";
  // import { useStore } from "vuex";
  import axios from "axios";
  import FontChangeTitle from "../components/shared/FontChangeTitle.vue";
  import ClassList from "../components/ClassList.vue";
  import SearchClasses from "../components/SearchClasses.vue";
  import AdvertisementTitle from "../components/AdvertisementTitle.vue";
  import AdvertisementContent from "../components/AdvertisementContent.vue";

  // searchClasses.vueで「検索」ボタンを押すととquery付きのpathでこのファイルのViewがrouter.pushで読み込まれる
  // path内のそのqueryをpathから取得し，格納する．
  // この情報を検索条件としてバックに投げる．
  const detailedCondition = ref(null);

  // 授業一覧内のリストに格納する授業情報リスト．バックから受けとった授業情報のリストをこの変数に格納し，classList.vueに送る
  const classDataList = ref([]);

  // const store = useStore();
  const route = useRoute();

  const searchClassByDetailedCondition = async () => {
    // path内のqueryから取得した値がdetailedConditionに格納されているはずなので，それが出力されるはず
    console.log("detailed condition is:");
    console.log(detailedCondition.value);
    try {
      const response = await axios.get("/api/searchByConditions", {
        params: {
          ...detailedCondition.value
        }
      });
      console.log("response");
      console.log(response);
      classDataList.value = response.data;
      console.log("class list data :");
      console.log(classDataList.value);
      // その他の処理
    } catch (error) {
      if (error.response) {
        // サーバーからのエラーレスポンスがある場合
        console.error(error.response.data); // エラーレスポンスをコンソールに出力
      } else {
        // リクエストがサーバーに届かなかった場合など
        console.error(error.message);
      }
    }
  };

  // 別のpathから移動してきたとき，またはページをリロード，初回読み込みの時にqueryパラメータからデータをフェッチ
  // onBeforeMount関数内で発火
  const fetchDataOnBeforeMount = async () => {
    detailedCondition.value = route.query;
    console.log("fetched data are");
    console.log(detailedCondition.value);
  };
  onBeforeMount(async () => {
    // 別のpathから移動してきたとき，またはページをリロード，初回読み込みの時に発火
    // onBeforeRouteUpdateか，この関数かどちらかが発火する
    console.log("before mount");
    await fetchDataOnBeforeMount(); //path内のqueryを取得し，detailedConditionに格納
    if (detailedCondition.value && Object.keys(detailedCondition.value).length > 0) {
      console.log("Before Mount: Data exists");
      searchClassByDetailedCondition();
    } else {
      console.log("Before Mount: detailed condition is null or empty");
    }
  });

  // 同じpathでqueryが異なるときにqueryを取得する関数
  // onBeforeRouteUpdateHandler関数内で発火
  const fetchDataOnBeforeRouteUpdate = async (query) => {
    detailedCondition.value = query;
    console.log("fetched data are");
    console.log(detailedCondition.value);
  };
  // 同じpathでqueryが異なるときに発火
  const onBeforeRouteUpdateHandler = (to, from, next) => {
    console.log("before route update");
    fetchDataOnBeforeRouteUpdate(to.query); // Pass the updated query parameters to fetchData
    if (detailedCondition.value && Object.keys(detailedCondition.value).length > 0) {
      console.log("Before Route Update: Data exists");
      searchClassByDetailedCondition();
    } else {
      console.log("Before Route Update: detailed condition is null or empty");
    }

    next();
  };

  onBeforeRouteUpdate(onBeforeRouteUpdateHandler);
</script>

<template>
  <v-container class="pb-8 bg-primary" fluid>
    <FontChangeTitle> 大学生による授業評価を、オープンに。 </FontChangeTitle>
    <AdvertisementTitle></AdvertisementTitle>
  </v-container>
  <!-- 検索機能 -->
  <SearchClasses></SearchClasses>

  <AdvertisementContent></AdvertisementContent>

  <!-- ここから検索で出てきた授業のリスト -->
  <ClassList :classDataList="classDataList"></ClassList>
</template>
