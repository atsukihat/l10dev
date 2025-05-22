<script setup>
  import { ref, watch, onMounted } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  import { useMessage } from "../components/composables/useMessage";
  import CommonAlert from "../components/shared/CommonAlert.vue";

  const router = useRouter();
  // どっちのタブを開くのか，情報を格納する変数
  const tab = ref(null);
  const { message, messageType, uniqueKey, setErrorMessage, setWarningMessage } = useMessage();

  // 条件で検索する際はこのデータをバックに送る
  const detailedCondition = ref({
    lectureName: null,
    teacherName: null,
    location: null,
    faculty: null,
    category: null,
    term: null,
    dayOfWeek: null,
    timePeriod: null,
    grade: null,
    totalEvaluation: {
      min: null,
      max: null
    },
    creditLevel: {
      min: null,
      max: null
    },
    interestLevel: {
      min: null,
      max: null
    },
    skillLevel: {
      min: null,
      max: null
    }
  });

  // TODO
  // 検索項目はフロントでもつ
  // 評価を3つに絞る
  // minとmaxを持たせる
  const locationList = ["東広島", "霞", "東千田", "双方向"];
  const facultyList = [
    "教養教育",
    "総合科学部",
    "文学部",
    "教育学部",
    "法学部",
    "経済学部",
    "理学部",
    "医学部",
    "歯学部",
    "薬学部",
    "工学部",
    "生物生産学部",
    "情報科学部",
    "大学院共通教育",
    "人間社会科学研究科",
    "先進理工系科学研究科",
    "統合生命科学研究科",
    "医系科学研究科",
    "スマートソサエティ実践科学研究科",
    "特別支援教育特別専攻科",
    "教育学研究科",
    "理学研究科",
    "医歯薬保健学研究科",
    "外国語教育センター",
    "総合博物館",
    "ライティングセンター"
  ];
  const categoryList = [
    "大学教育入門",
    "展開ゼミ",
    "平和科目",
    "外国語科目",
    "情報・データサイエンス科目",
    "領域科目",
    "基盤科目",
    "社会連携科目",
    "健康スポーツ科目",
    "専門教育科目",
    "大学院共通科目",
    "専門的教育科目",
    "他学部・他研究科科目"
  ];
  const termList = ["1T", "2T", "3T", "4T", "集中", "前期", "後期", "通年"];
  const dayOfWeekList = ["月", "火", "水", "木", "金"];
  const timePeriodList = ["1コマ", "2コマ", "3コマ", "4コマ", "5コマ", "6コマ", "7コマ"];
  const gradeList = ["B1", "B2", "B3", "B4", "B5"];

  const evaluationRateList = [
    {
      title: "5",
      rate: "5"
    },
    {
      title: "4",
      rate: "4"
    },
    {
      title: "3",
      rate: "3"
    },
    {
      title: "2",
      rate: "2"
    },
    {
      title: "1",
      rate: "1"
    }
  ];

  const creditRateList = [
    {
      title: "超楽",
      rate: "5"
    },
    {
      title: "楽",
      rate: "4"
    },
    {
      title: "普通",
      rate: "3"
    },
    {
      title: "難",
      rate: "2"
    },
    {
      title: "激難",
      rate: "1"
    }
  ];

  const interestRateList = [
    {
      title: "とても面白い",
      rate: "5"
    },
    {
      title: "面白い",
      rate: "4"
    },
    {
      title: "普通",
      rate: "3"
    },
    {
      title: "面白くない",
      rate: "2"
    },
    {
      title: "全く面白くない",
      rate: "1"
    }
  ];

  const skillRateList = [
    {
      title: "とても役立つ",
      rate: "5"
    },
    {
      title: "役立つ",
      rate: "4"
    },
    {
      title: "普通",
      rate: "3"
    },
    {
      title: "あまり役に立たない",
      rate: "2"
    },
    {
      title: "全く役に立たない",
      rate: "1"
    }
  ];

  //講義コードで検索する際はこのデータをバックに送る
  const searchClassByLectureCode = ref({
    lectureCode: null
  });

  // オブジェクトからvalueがNULLのkeyとvalueのペアを削除する関数
  function removeNullValues(obj) {
    const result = {};

    for (const key in obj) {
      const value = obj[key];

      if (value !== null) {
        result[key] = value;
      }
    }

    return result;
  }

  // オブジェクトのkeyに対応するすべてのvalueがnullであるか確かめる関数
  // 全てnullであればtrue, 1つでもnull以外のものがあればfalseを返す．
  // オブジェクトが入れ子構造になっていても実行可能
  const isAllNull = (obj) => {
    for (const key in obj) {
      if (typeof obj[key] === "object") {
        if (!isAllNull(obj[key])) {
          return false;
        }
      } else {
        if (obj[key] !== null) {
          return false;
        }
      }
    }
    return true;
  };

  // 数字の大小で検索するとき，大小関係が自然であることを判定する関数
  const isCorrectMinMax = (objectName) => {
    const min = detailedCondition.value[objectName].min;
    const max = detailedCondition.value[objectName].max;
    if (min === null || max === null) {
      return true;
    } else if (min <= max) {
      return true;
    } else {
      return false;
    }
  };

  // 全ての大小関係がそろっているか確認する関数
  const allCorrectMinMax = () => {
    if (
      isCorrectMinMax("totalEvaluation") &&
      isCorrectMinMax("creditLevel") &&
      isCorrectMinMax("interestLevel") &&
      isCorrectMinMax("skillLevel")
    ) {
      return true;
    } else {
      return false;
    }
  };

  // 条件で検索するボタンが押されたときに発火する関数
  //検索条件を/class（classListView.vue）のpath内のクエリとして，router.pushされた後はそのqueryをClassListView.vueが受け取って処理する
  const sendQueryToClassListView = async () => {
    if (
      //バリデーションチェック(どれか1つでも入力していたら(nullでなければ)通過)
      isAllNull(detailedCondition.value)
    ) {
      //バリデーションが通らなかったときに実行
      setWarningMessage("検索する条件を入力してください");
    } else if (!allCorrectMinMax()) {
      setWarningMessage("評価値の大小関係を修正してください");
    } else {
      //バリデーション通過時に実行

      const query = {
        lectureName: detailedCondition.value.lectureName,
        teacherName: detailedCondition.value.teacherName,
        location: detailedCondition.value.location,
        faculty: detailedCondition.value.faculty,
        category: detailedCondition.value.category,
        term: detailedCondition.value.term,
        dayOfWeek: detailedCondition.value.dayOfWeek,
        timePeriod: detailedCondition.value.timePeriod,
        grade: detailedCondition.value.grade,
        totalEvaluationMin: detailedCondition.value.totalEvaluation.min,
        totalEvaluationMax: detailedCondition.value.totalEvaluation.max,
        creditLevelMin: detailedCondition.value.creditLevel.min,
        creditLevelMax: detailedCondition.value.creditLevel.max,
        interestLevelMin: detailedCondition.value.interestLevel.min,
        interestLevelMax: detailedCondition.value.interestLevel.max,
        skillLevelMin: detailedCondition.value.skillLevel.min,
        skillLevelMax: detailedCondition.value.skillLevel.max
      };
      // クエリからNULLをなくす
      const filteredQuery = removeNullValues(query);

      router.push({
        name: "class",
        query: filteredQuery
      });
    }
  };

  // 講義コードで検索する際に発火する関数
  const searchByLectureCode = async () => {
    try {
      const response = await axios.post("/api/hasLectureCode", searchClassByLectureCode.value);
      console.log("response");
      console.log(response);
      if (response.data.success) {
        const lectureId = response.data.lectureId;
        router.push({ name: "class-detail", params: { lectureId: lectureId } });
      } else {
        setErrorMessage("存在しない講義コードです．");
      }

      // その他の処理
    } catch (error) {
      if (error.response) {
        // サーバーからのエラーレスポンスがある場合
        setErrorMessage(error.response.data.message); // エラーメッセージを表示
      } else {
        // リクエストがサーバーに届かなかった場合など
        setErrorMessage("エラーが発生しました。時間をおいて再度お試しください。");
      }
    }
  };

  // ここから検索候補を表示する機能に関するコード
  const candidateConditionsList = ref([]);
  //
  const fetchData = async () => {
    try {
      const response = await axios.get("/api/getLectureInfo");
      console.log("response from get lecture info : 検索候補のデータ");
      console.log(response);
      // 検索候補をバックエンドから取得して格納
      candidateConditionsList.value = response.data; // 仮に response.data が候補条件のリストであると仮定
      makeDefaultCandidateLectureNameList();
    } catch (error) {
      if (error.response) {
        // サーバーからのエラーレスポンスがある場合
        setErrorMessage(error.response.data.message); // エラーメッセージを表示
      } else {
        // リクエストがサーバーに届かなかった場合など
        setErrorMessage("エラーが発生しました。時間をおいて再度お試しください。");
      }
    }
  };

  onMounted(() => {
    fetchData();
  });

  const filteredCandidateConditionsList = ref([]);
  const candidateLectureNameList = ref([]);
  const candidateTeacherNameList = ref([]);

  const filterCandidateConditions = () => {
    const detailedLectureName = detailedCondition.value.lectureName
      ? detailedCondition.value.lectureName.toLowerCase()
      : "";
    const detailedTeacherName = detailedCondition.value.teacherName
      ? detailedCondition.value.teacherName.toLowerCase()
      : "";

    filteredCandidateConditionsList.value = candidateConditionsList.value.filter((item) => {
      const lowerCaseLectureName = item.lectureName.toLowerCase();
      const lowerCaseTeacherName = item.teacherName.toLowerCase();

      return lowerCaseLectureName.includes(detailedLectureName) && lowerCaseTeacherName.includes(detailedTeacherName);
    });
  };

  // detailedCondition.value.lectureNameまたはdetailedCondition.value.teacherNameが変化したら実行
  watch([() => detailedCondition.value.lectureName, () => detailedCondition.value.teacherName], () => {
    filterCandidateConditions();
    // このコメントアウトは保存してください！
    // 検索候補をすべて消去したら候補を表示しなくなる条件分岐
    // if (detailedCondition.value.lectureName === null && detailedCondition.value.teacherName === null) {
    //   candidateLectureNameList.value = [];
    //   candidateTeacherNameList.value = [];
    // } else {
    //   filterCandidateConditions();
    // }
  });

  // filteredCandidateConditionsListが更新されたら実行
  watch(
    () => filteredCandidateConditionsList.value,
    () => {
      // 値をクリア
      candidateLectureNameList.value = [];
      candidateTeacherNameList.value = [];

      // 更新されたリストを走査して値を抽出
      filteredCandidateConditionsList.value.forEach((item) => {
        candidateLectureNameList.value.push(item.lectureName);
        candidateTeacherNameList.value.push(item.teacherName);
      });

      // 重複を取り除く
      candidateLectureNameList.value = [...new Set(candidateLectureNameList.value)];
      candidateTeacherNameList.value = [...new Set(candidateTeacherNameList.value)];
    }
  );

  // 検索候補の初期設定
  // デフォルトの検索候補はすべての授業名・担当教員名である
  const makeDefaultCandidateLectureNameList = () => {
    // candidateLectureNameList.value = [];
    // candidateTeacherNameList.value = [];
    filterCandidateConditions();
  };

  // 検索条件をクリアする関数
  const clearAllConditions = () => {
    // すべての条件をnullに設定
    detailedCondition.value = {
      lectureName: null,
      teacherName: null,
      location: null,
      faculty: null,
      category: null,
      term: null,
      dayOfWeek: null,
      timePeriod: null,
      grade: null,
      totalEvaluation: {
        min: null,
        max: null
      },
      creditLevel: {
        min: null,
        max: null
      },
      interestLevel: {
        min: null,
        max: null
      },
      skillLevel: {
        min: null,
        max: null
      }
    };
  };
</script>

<template>
  <v-container class="mb-8 custom-background" fluid>
    <v-row justify="center">
      <v-col>
        <v-card max-width="900px" class="mx-auto" elevation="5">
          <v-tabs v-model="tab" align-tabs="center" height="60px">
            <v-tab value="one" class="tab-name w-50"><p class="text-subtitle-1 text-md-h6">条件で探す</p></v-tab>
            <v-tab value="two" class="tab-name w-50"><p class="text-subtitle-1 text-md-h6">講義コードで探す</p></v-tab>
          </v-tabs>

          <v-card-text class="px-0 px-sm-4 px-md-4 px-lg-4">
            <v-window v-model="tab">
              <v-window-item value="one">
                <v-container class="category-name-and-content-container">
                  <p class="category-name">授業名</p>
                  <v-combobox
                    v-model="detailedCondition.lectureName"
                    clearable
                    placeholder="一攫千金特論"
                    :items="candidateLectureNameList"
                    variant="outlined"
                    prepend-inner-icon="mdi-magnify"
                  ></v-combobox>
                </v-container>
                <v-container class="category-name-and-content-container">
                  <p class="category-name">担当教員名</p>
                  <v-combobox
                    v-model="detailedCondition.teacherName"
                    clearable
                    placeholder="広大太郎"
                    :items="candidateTeacherNameList"
                    variant="outlined"
                    prepend-inner-icon="mdi-magnify"
                  ></v-combobox>
                </v-container>
                <!-- 他の条件についても同様にコードを追加 -->

                <v-expansion-panels class="mb-3">
                  <v-expansion-panel elevation="0" bg-color="" collapse-icon="mdi-magnify">
                    <v-expansion-panel-title color="third">
                      <v-row>
                        <v-col cols="12" class="d-flex justify-center">
                          <p class="pl-5">詳細検索</p>
                        </v-col>
                      </v-row>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text class="class-v-expansion-panel-text">
                      <!-- 以下、他の条件の追加 -->

                      <!-- 大学に関する区分（開講部局，開講区分，開講場所） -->
                      <v-row class="my-3">
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">開講場所</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.location"
                              :items="locationList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">開講部局</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.faculty"
                              :items="facultyList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">開講区分</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.category"
                              :items="categoryList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                      </v-row>

                      <!-- 時間に関する区分（ターム，曜日，時間） -->
                      <v-row class="my-3">
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">ターム</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.term"
                              :items="termList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">曜日</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.dayOfWeek"
                              :items="dayOfWeekList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">時間</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.timePeriod"
                              :items="timePeriodList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                      </v-row>

                      <!-- 履修年次に関する区分 -->
                      <v-row class="my-3">
                        <v-col cols="12" sm="12" md="4" lg="4" class="px-5">
                          <v-row>
                            <p class="category-name">履修年次</p>
                          </v-row>
                          <v-row>
                            <v-select
                              v-model="detailedCondition.grade"
                              :items="gradeList"
                              class="pulldown-list no-margin-padding"
                              clearable
                              variant="outlined"
                            ></v-select>
                          </v-row>
                        </v-col>
                      </v-row>
                      <!-- ここから上下関係に関する部分．初期リリースでは利用しないのでv-if属性をfalseにして非表示化します -->
                      <div v-if="false">
                        <!-- 区切り線 -->
                        <!-- <v-divider :thickness="1" class="border-opacity-100" color="primary"></v-divider> -->

                        <!-- 総合評価 -->
                        <v-row class="my-3">
                          <v-col>
                            <v-row>
                              <v-col cols="12">
                                <p class="category-name d-flex justify-center">総合評価</p>
                              </v-col>
                            </v-row>
                            <v-row>
                              <!-- 下限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.totalEvaluation.min"
                                  label="下限"
                                  :items="evaluationRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                              <v-col cols="1" class="d-flex justify-center mt-4">
                                <p>〜</p>
                              </v-col>
                              <!-- 上限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.totalEvaluation.max"
                                  label="上限"
                                  :items="evaluationRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>
                        <!-- 単位取得のしやすさ -->
                        <v-row class="my-3">
                          <v-col>
                            <!-- 表題 -->
                            <v-row>
                              <v-col cols="12">
                                <p class="category-name d-flex justify-center">単位取得のしやすさ</p>
                              </v-col>
                            </v-row>
                            <v-row>
                              <!-- 下限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.creditLevel.min"
                                  label="下限"
                                  :items="creditRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                              <v-col cols="1" class="d-flex justify-center mt-4">
                                <p>〜</p>
                              </v-col>
                              <!-- 上限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.creditLevel.max"
                                  label="上限"
                                  :items="creditRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>

                        <!-- 面白さ -->
                        <v-row>
                          <v-col>
                            <!-- 表題 -->
                            <v-row>
                              <v-col cols="12">
                                <p class="category-name d-flex justify-center">面白さ</p>
                              </v-col>
                            </v-row>
                            <v-row>
                              <!-- 下限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.interestLevel.min"
                                  label="下限"
                                  :items="interestRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                              <v-col cols="1" class="d-flex justify-center mt-4">
                                <p>〜</p>
                              </v-col>
                              <!-- 上限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.interestLevel.max"
                                  label="上限"
                                  :items="interestRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>

                        <!-- スキルが身につくか -->
                        <v-row>
                          <v-col>
                            <!-- 表題 -->
                            <v-row>
                              <v-col cols="12">
                                <p class="category-name d-flex justify-center">スキルが身につくか</p>
                              </v-col>
                            </v-row>
                            <v-row>
                              <!-- 下限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.skillLevel.min"
                                  label="下限"
                                  :items="skillRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                              <v-col cols="1" class="d-flex justify-center mt-4">
                                <p>〜</p>
                              </v-col>
                              <!-- 上限 -->
                              <v-col cols="5.5">
                                <v-select
                                  v-model="detailedCondition.skillLevel.max"
                                  label="上限"
                                  :items="skillRateList"
                                  item-value="rate"
                                  item-text="title"
                                  class="pulldown-list no-margin-padding class-v-select"
                                  clearable
                                  variant="outlined"
                                ></v-select>
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>
                      </div>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
                <v-row justify="center" align="center">
                  <v-col class="d-flex justify-center">
                    <v-btn color="error" class="mr-4" @click="clearAllConditions">
                      <v-icon start icon="mdi-delete" color="white"></v-icon>
                      <p class="text-white">条件をクリア</p>
                    </v-btn>
                    <v-btn color="primary" @click="sendQueryToClassListView">
                      <v-icon start icon="mdi-magnify" color="white"></v-icon>
                      <p class="text-white">検索</p>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-window-item>

              <v-window-item value="two">
                <v-container class="category-and-content-container">
                  <p class="category-name">講義コード</p>
                  <v-text-field
                    v-model="searchClassByLectureCode.lectureCode"
                    placeholder="KA*******"
                    clearable
                    variant="outlined"
                  ></v-text-field>
                </v-container>
                <v-row justify="center" align="center">
                  <v-col cols="12" class="d-flex justify-center">
                    <v-btn color="primary" @click="searchByLectureCode">
                      <v-icon start icon="mdi-magnify" color="white"></v-icon>
                      <p class="text-white">検索</p>
                    </v-btn>
                  </v-col>
                </v-row>
              </v-window-item>
            </v-window>
          </v-card-text>
        </v-card>
        <common-alert :message="message" :type="messageType" :unique-key="uniqueKey" />
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
  /* .category-name {
    @apply text-md-h5 text-sm-h6;
  } */

  .no-margin-padding {
    margin: 0 !important;
    padding: 0 !important;
  }
  .tab-name {
    font-size: 1.3rem;
  }
  .pulldown-list {
    margin: 20px;
  }

  .custom-background {
    background: linear-gradient(to bottom, #ff971d 60px, /* 上部20%の色 */ #ffffff 60px /* 20%以降の色 */);
  }
</style>
