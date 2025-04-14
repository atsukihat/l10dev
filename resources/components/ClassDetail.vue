<script setup>
  import { defineProps, ref } from "vue";
  import { useRouter } from "vue-router";
  import ReviewListVue from "./ReviewList.vue";
  import ClassInformationTable from "./ClassInformationTable.vue";
  import RadarChart from "./RadarChart.vue";
  import BarGraph from "./BarGraph.vue";
  import Admax from "../components/Admax.vue";

  const { classDetailData } = defineProps(["classDetailData"]);
  const router = useRouter();

  const activeTab = ref("btn-1");

  const panel = ref(0);
</script>

<template>
  <v-row class="mb-15">
    <v-col cols="12">
      <v-row justify="center">
        <v-col cols="12">
          <!-- 文字の周りに余白を持たせるためのコンポーネント -->
          <v-container>
            <p class="text-h6 text-sm-h5 text-md-h4 text-lg-h4 text-center">
              {{ classDetailData?.classInformationData?.lectureName }}
            </p>
          </v-container>
        </v-col>
        <v-col cols="12" class="d-flex flex-row justify-center">
          <!-- レビューが投稿済みかどうかによって表示する文言を変更する -->
          <p v-if="classDetailData.classInformationData.alreadyReviewed">レビュー投稿済みです</p>
          <v-btn v-else class="d-flex flex-row ml-5" color="primary"
            ><v-icon color="white">mdi-pencil</v-icon>
            <p
              class="text-h9 text-white"
              @click="
                router.push({
                  name: 'class/post',
                  query: {
                    lectureName: classDetailData?.classInformationData?.lectureName,
                    teacherName: classDetailData?.classInformationData?.teacherName
                  }
                })
              "
            >
              レビュー投稿
            </p>
          </v-btn>
        </v-col>
      </v-row>
      <v-row>
        <v-col v-if="classDetailData">
          <v-expansion-panels v-model="panel">
            <v-expansion-panel>
              <v-expansion-panel-title> 基本情報 </v-expansion-panel-title>
              <v-expansion-panel-text>
                <v-row>
                  <v-col>
                    <ClassInformationTable
                      :class-information-data="classDetailData?.classInformationData"
                    ></ClassInformationTable>
                  </v-col>
                </v-row>
                <!-- 区切り線 -->
                <v-divider :thickness="1" class="border-opacity-100 my-8" color="primary"></v-divider>
                <v-row justify="center">
                  <v-col cols="12" class="d-flex justify-center">
                    <v-btn-toggle
                      v-model="activeTab"
                      color="primary"
                      class="d-flex flex-wrap justify-center class-v-btn-toggle"
                    >
                      <v-btn value="btn-1" rounded="xl" class="class-v-btn" height="50px">5段階評価</v-btn>
                      <v-btn value="btn-2" rounded="xl" class="class-v-btn" height="50px">みんなの成績</v-btn>
                      <v-btn value="btn-3" rounded="xl" class="class-v-btn" height="50px">出欠確認</v-btn>
                      <v-btn value="btn-4" rounded="xl" class="class-v-btn" height="50px">毎回のレポート・テスト</v-btn>
                      <v-btn value="btn-5" rounded="xl" class="class-v-btn" height="50px">中間のレポート・テスト</v-btn>
                      <v-btn value="btn-6" rounded="xl" class="class-v-btn" height="50px">期末のレポート・テスト</v-btn>
                      <v-btn value="btn-7" rounded="xl" class="class-v-btn" height="50px"
                        >過去問・過去レポートの所持</v-btn
                      >
                    </v-btn-toggle>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col class="d-flex justify-center" cols="12">
                    <v-window v-model="activeTab">
                      <v-window-item value="btn-1">
                        <RadarChart :radar-chart-data="classDetailData?.classRadarChartData"></RadarChart>
                      </v-window-item>
                      <v-window-item value="btn-2">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.grades"
                          chart-title="みんなの成績"
                        ></BarGraph>
                      </v-window-item>
                      <v-window-item value="btn-3">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.attendanceConfirm"
                          chart-title="出欠確認"
                        ></BarGraph>
                      </v-window-item>
                      <v-window-item value="btn-4">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.weeklyAssignments"
                          chart-title="毎回のレポート・テスト"
                        ></BarGraph>
                      </v-window-item>
                      <v-window-item value="btn-5">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.midtermAssignments"
                          chart-title="中間のレポート・テスト"
                        ></BarGraph>
                      </v-window-item>
                      <v-window-item value="btn-6">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.finalAssignments"
                          chart-title="期末のレポート・テスト"
                        ></BarGraph>
                      </v-window-item>
                      <v-window-item value="btn-7">
                        <BarGraph
                          :bar-graph-data="classDetailData?.classBarGraphData?.pastExamPossesion"
                          chart-title="過去問・過去レポートの所持"
                        ></BarGraph>
                      </v-window-item>
                    </v-window>
                  </v-col>
                </v-row>
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-col>
        <v-col v-else>
          <p>データを取得できませんでした．</p>
        </v-col>
      </v-row>
      <Admax></Admax>
      <ReviewListVue :review-data-list="classDetailData?.reviewDataList"></ReviewListVue>
    </v-col>
  </v-row>
</template>

<style scoped>
  .v-btn-group--density-default.v-btn-group {
    height: auto;
  }
</style>
