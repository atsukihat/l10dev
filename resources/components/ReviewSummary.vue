<script setup>
  import { ref } from "vue";
  import axios from "axios";
  import { useStore } from "vuex";

  import RadarChart from "./RadarChart.vue";
  import StarRading from "./StarRating.vue";
  import MenuItem from "./shared/MenuItem.vue";
  import ConfirmDialog from "./shared/ConfirmDialog.vue";
  import { useRouter } from "vue-router";

  const router = useRouter();
  const store = useStore();
  const props = defineProps(["reviewData"]);

  const reviewUserId = ref(null);
  const requestUserId = ref(null);
  const requestReviewId = ref(null);

  reviewUserId.value = props.reviewData.userId;
  // ログインしていればユーザーのIDを使用して、そうでなければIDをしようしない(nullを使用する)
  requestUserId.value = store.getters.userInfo ? store.getters.userInfo.id : null;
  requestReviewId.value = props.reviewData.reviewId;

  console.log("reviewUserId:", reviewUserId.value, "Type:", typeof reviewUserId.value);
  console.log("requestUserId:", requestUserId.value, "Type:", typeof requestUserId.value);
  console.log("reviewUserId === requestUserId:", reviewUserId.value === requestUserId.value);

  const radarChartData = {
    creditLevel: props.reviewData.creditLevel,
    interestLevel: props.reviewData.interestLevel,
    skillLevel: props.reviewData.skillLevel
  };

  const menuItems = ref([
    // { title: "編集する", action: "edit", style: "" },
    { title: "削除する", action: "delete", style: "color: red" }
  ]);

  const isDialogVisible = ref(false);

  const handleMenuItemClick = async (item) => {
    console.log("Menu item clicked:", item.title);
    try {
      if (item.action === "edit") {
        // 編集ページへの遷移処理をここに記述
        // router.push({ name: 'EditPage', params: { reviewId: props.reviewData.id } });
      } else if (item.action === "delete") {
        isDialogVisible.value = true;
      }
    } catch (error) {
      console.error("API call failed:", error);
    }
  };

  // ダイアログの削除ボタンのクリックイベント
  const deleteReview = async () => {
    try {
      const response = await axios.delete(`/api/deleteReview/${requestReviewId.value}`);
      isDialogVisible.value = false; // ダイアログを閉じる
      router.go(0); // リロードする際には以下のコード
    } catch (error) {
      // console.error("API call failed:", error);
      isDialogVisible.value = false; // エラー発生時もダイアログを閉じる
    }
  };

  // ダイアログのキャンセルボタンのクリックイベント
  const cancelDelete = () => {
    isDialogVisible.value = false; // ダイアログを閉じる
  };
</script>

<template>
  <v-row>
    <v-col cols="12" class="pa-2">
      <v-card variant="outlined" class="ma-0">
        <v-container>
          <v-row>
            <!-- ユーザ名・授業名・星評価 -->
            <v-col cols="10.5">
              <v-row justify="start" align="center">
                <v-col cols="12" sm="12" md="12" lg="12" class="pb-0">
                  <p class="text-h11">
                    <span class="text-h6 text-blue">{{ reviewData.userName }}</span> :
                    <span class="text-h11 text-grey-darken-2">{{ reviewData.lectureName }}</span>
                  </p>
                </v-col>
              </v-row>
              <v-row justify="start" align="center">
                <v-col cols="12" sm="12" md="12" lg="12">
                  <StarRading :total-evaluation="reviewData.totalEvaluation"></StarRading>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" class="pb-0">
                  <p class="text-h10">
                    {{ reviewData.comments }}
                  </p>
                </v-col>
              </v-row>
              <v-row justify="start" align="center">
                <v-col cols="12" sm="5" md="3" lg="3" class="pb-0">
                  <p class="text-h10 text-grey">受講年度：{{ reviewData.attendanceYear }}年</p>
                </v-col>
              </v-row>
            </v-col>
            <!-- 編集・削除ボタン -->
            <v-col cols="1" class="pt-0" justify="end">
              <v-btn v-if="reviewUserId === requestUserId" icon="mdi-dots-horizontal" variant="text"> </v-btn>
              <!-- コンポーネントの再利用 -->
              <menu-item
                v-if="reviewUserId === requestUserId"
                :menu-items="menuItems"
                :on-menu-item-click="handleMenuItemClick"
                location="left"
              ></menu-item>
              <!-- 編集・削除のモーダル -->
              <confirm-dialog
                :is-dialog-visible="isDialogVisible"
                @confirm="deleteReview"
                @cancel="cancelDelete"
                message-in-dialog="このレビューを削除してもよろしいですか？"
              ></confirm-dialog>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="" md="" lg="" class="pa-3">
              <details class="detail-toggle">
                <!-- summaryタグを使用してトグルみたいにした -->
                <summary class="text-h7">評定・カテゴリ別評価</summary>
                <v-row justify="start">
                  <v-col cols="12" sm="6" md="6" lg="6">
                    <p class="text-h10 text-grey ml-5">投稿日：{{ reviewData.createdAt }}</p>
                  </v-col>
                </v-row>
                <v-row justify="center">
                  <v-col cols="12" sm="12" md="12" lg="6">
                    <v-table class="ma-5 review-table">
                      <tbody>
                        <tr>
                          <td>評定</td>
                          <td>{{ reviewData.grades }}</td>
                        </tr>
                        <tr>
                          <td>出欠確認</td>
                          <td>{{ reviewData.attendanceConfirm }}</td>
                        </tr>
                        <tr>
                          <td>過去問の所持</td>
                          <td>{{ reviewData.pastExamPossession }}</td>
                        </tr>
                        <tr>
                          <td>日々の課題</td>
                          <td>{{ reviewData.weeklyAssignments }}</td>
                        </tr>
                        <tr>
                          <td>中間レポ・テスト</td>
                          <td>{{ reviewData.midtermAssignments }}</td>
                        </tr>
                        <tr>
                          <td>期末レポ・テスト</td>
                          <td>{{ reviewData.finalAssignments }}</td>
                        </tr>
                      </tbody>
                    </v-table>
                  </v-col>
                  <v-col cols="12" sm="12" md="12" lg="5">
                    <div class="radar-chart-in-review"><RadarChart class="ma-5" :radar-chart-data="radarChartData"></RadarChart></div>
                  </v-col>
                </v-row>
              </details>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-col>
  </v-row>
</template>

<style>
  .detail-toggle {
    background-color: none;
    border-radius: 2px;
  }
  .review-table {
    background-color: #eceff1;
  }
  .radar-chart-in-review{
    width: 100%;
    height: 400px;
    position: relative;
    right: 20px;
  }
</style>
