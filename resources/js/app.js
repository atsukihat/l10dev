import { createApp } from "vue/dist/vue.esm-bundler.js";
import App from "../views/App.vue";
import LoginView from "../views/LoginView.vue";
import WelcomeView from "../views/WelcomeView.vue";
import ProfileView from "../views/ProfileView.vue";
import RegisterView from "../views/RegisterView.vue";
import ClassListView from "../views/ClassListView.vue";
import ProfileEditView from "../views/ProfileEditView.vue";
import ClassDetailView from "../views/ClassDetailView.vue";
import ClassPostView from "../views/ClassPostView.vue";
import PasswordResetRequestView from "../views/PasswordResetRequestView.vue";
import PasswordResetView from "../views/PasswordResetView.vue";
import PrivacyPolicyView from "../views/PrivacyPolicyView.vue";
import TermsView from "../views/TermsView.vue";
import ContactView from "../views/ContactView.vue";
import NotFoundView from "../views/NotFoundView.vue";
import store from "../store/index.js";
import { createRouter, createWebHistory } from "vue-router";

// Vuetify
import "vuetify/styles";
import "@mdi/font/css/materialdesignicons.css";
import vuetify from "./plugins/vuetify";
import "./../assets/font.css";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: "/", name: "welcome", component: WelcomeView },
    { path: "/register", name: "register", component: RegisterView },
    { path: "/login", name: "login", component: LoginView },
    // 後々/profileにmeta: { requiresAuth: true },を追加する(ログインしていないと見れない)
    { path: "/profile", name: "profile", component: ProfileView, meta: { requiresAuth: true } },
    { path: "/profile/edit", name: "profile/edit", component: ProfileEditView, meta: { requiresAuth: true } },
    { path: "/class", name: "class", component: ClassListView },
    {
      path: "/class/:lectureId/detail",
      name: "class-detail",
      component: ClassDetailView,
      props: true
    },
    { path: "/class/post", name: "class/post", component: ClassPostView, meta: { requiresAuth: true } },
    { path: "/password/reset-request", name: "password-reset-request", component: PasswordResetRequestView },
    { path: "/password/reset/:token", name: "password-reset", component: PasswordResetView },
    { path: "/privacy-policy", name: "privacy-policy", component: PrivacyPolicyView },
    { path: "/terms", name: "terms", component: TermsView },
    { path: "/contact", name: "contact", component: ContactView },
    { path: "/:catchAll(.*)", name: "notFound", component: NotFoundView },
    { path: "/:catchAll(.*)", name: "not-found", component: NotFoundView }
    // 設定画面を追加する.
  ]
});

router.beforeEach((to, from, next) => {
  // Check if the route requires authentication
  if (to.meta.requiresAuth) {
    // Check if the user is logged in
    if (store.getters.isLoggedIn) {
      // User is authenticated, proceed to the route
      next();
    } else {
      // User is not logged in, redirect to the login page
      next("/login");
    }
  } else {
    // Route does not require authentication, proceed
    next();
  }
});

createApp(App).use(vuetify).use(router).use(store).mount("#app");
