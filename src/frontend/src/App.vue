<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4">
      <h1 class="text-3xl font-bold">Tray Test</h1>
    </header>

    <!-- Conteúdo -->
    <main class="flex-1 p-6">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white p-4 text-center">
      <p>© 2025 Tray Test</p>
    </footer>
  </div>
</template>

<script lang="ts">
import GoogleLogin from './components/GoogleLogin.vue';
import { useRouter } from 'vue-router';
import {onMounted, ref} from "vue";

export default {
  components: {
    GoogleLogin
  },
  setup() {
    const token = ref<string | null>(null);
    const redirectTo = ref<string | null>(null);
    const router = useRouter();

    onMounted(() => {
      const urlParams = new URLSearchParams(window.location.search);
      token.value = urlParams.get('token');
      redirectTo.value = urlParams.get('redirect');

      if (urlParams.has('token')) {
        localStorage.setItem("token", token.value);
      }

      if (redirectTo.value) {
        router.push({ name: redirectTo.value });
      }
    });
    return { token, redirectTo };
  },
};
</script>
