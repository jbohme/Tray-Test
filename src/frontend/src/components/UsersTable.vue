<template>
  <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-semibold text-center mb-6">Registros de Usuários</h2>

    <!-- Filtros -->
    <div class="flex mb-6 space-x-4">
      <div class="flex-1">
        <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
        <input
          v-model="filters.cpf"
          type="text"
          id="cpf"
          placeholder="CPF"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div class="flex-1">
        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
        <input
          v-model="filters.name"
          type="text"
          id="name"
          placeholder="Nome"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <button
        @click="fetchUsers(1)"
      class="mt-5 px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
      Filtrar
      </button>
    </div>

    <!-- Tabela de usuários -->
    <table class="min-w-full table-auto border-collapse">
      <thead>
      <tr>
        <th class="px-4 py-2 border-b">Nome</th>
        <th class="px-4 py-2 border-b">Data de Nascimento</th>
        <th class="px-4 py-2 border-b">Email</th>
        <th class="px-4 py-2 border-b">CPF</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in users" :key="user.email">
        <td class="px-4 py-2 border-b">{{ user.name }}</td>
        <td class="px-4 py-2 border-b">{{ user.birthdate }}</td>
        <td class="px-4 py-2 border-b">{{ user.email }}</td>
        <td class="px-4 py-2 border-b">{{ user.cpf }}</td>
      </tr>
      </tbody>
    </table>

    <!-- Paginação -->
    <div v-if="pagination" class="mt-6 flex justify-between items-center">
      <button
        @click="goToPage(pagination.current_page - 1)"
        :disabled="pagination.current_page <= 1"
        class="px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-md hover:bg-gray-400 disabled:opacity-50"
      >
        Anterior
      </button>
      <span class="text-gray-700">
        Página {{ pagination.current_page }} de {{ pagination.last_page }}
      </span>
      <button
        @click="goToPage(pagination.current_page + 1)"
        :disabled="pagination.current_page >= pagination.last_page"
        class="px-4 py-2 bg-gray-300 text-gray-600 font-semibold rounded-md hover:bg-gray-400 disabled:opacity-50"
      >
        Próxima
      </button>
    </div>

    <div v-if="errorMessage" class="text-red-500 mt-4">{{ errorMessage }}</div>
  </div>
</template>

<script lang="ts">

import axios from 'axios';
export default {
  data() {
    return {
      users: [],
      pagination: null,
      filters: {
        cpf: '',
        name: '',
      },
      errorMessage: '',
    };
  },
  methods: {
    async fetchUsers(page: number = 1) {
      try {
        const token = localStorage.getItem('token');
        if (!token) {
          this.errorMessage = 'Token não encontrado.';
          return;
        }
        const params: any = { page: page };

        if (this.filters.cpf) params.cpf = this.filters.cpf;
        if (this.filters.name) params.name = this.filters.name;

        const response = await axios.get(
          `${import.meta.env.VITE_API_URL}/api/users`,
          {
            params: params,
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.users = response.data.data;
        this.pagination = response.data.pagination;
      } catch (error) {
        if (error.response && error.response.data.message) {
          this.errorMessage = `Erro: ${error.response.data.message}`;
        } else {
          this.errorMessage = 'Erro inesperado ao buscar os usuários.';
        }
      }
    },

    goToPage(page: number) {
      if (page < 1 || (this.pagination && page > this.pagination.last_page)) {
        return;
      }
      this.fetchUsers(page);
    },
  },

  mounted() {
    this.fetchUsers();
  },
};
</script>

<style scoped>
</style>
