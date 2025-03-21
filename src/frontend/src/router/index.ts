import { createRouter, createWebHistory } from 'vue-router';
import UsersTable from "@/components/UsersTable.vue";
import GoogleLogin from "@/components/GoogleLogin.vue";
import CompleteProfile from "@/components/CompleteProfile.vue";

const routes = [
  { path: '/', component: GoogleLogin },
  { path: '/usuarios', name: 'usuarios', component: UsersTable },
  { path: "/finalizar-cadastro", name: 'finalizar-cadastro', component: CompleteProfile },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router
