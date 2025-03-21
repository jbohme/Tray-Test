<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-semibold text-center mb-6">Cadastro Complementar</h2>

    <form @submit.prevent="submitForm">
      <!-- Nome -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
        <input
          v-model="form.name"
          type="text"
          id="name"
          placeholder="José Castro"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div v-if="errors.name" class="text-red-500 text-sm mt-2">{{ errors.name }}</div>
      </div>

      <!-- CPF -->
      <div class="mb-4">
        <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
        <input
          v-model="form.cpf"
          type="text"
          id="cpf"
          placeholder="445.484.179-00"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div v-if="errors.cpf" class="text-red-500 text-sm mt-2">{{ errors.cpf }}</div>
      </div>

      <!-- Data de Nascimento -->
      <div class="mb-4">
        <label for="birthdate" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
        <input
          v-model="form.birthdate"
          type="text"
          id="birthdate"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div v-if="errors.birthdate" class="text-red-500 text-sm mt-2">{{ errors.birthdate }}</div>
      </div>

      <div v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</div>

      <div class="mt-6 flex justify-center">
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Atualizar Dados
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        name: '',
        cpf: '',
        birthdate: '',
      },
      errors: {
        name: '',
        cpf: '',
        birthdate: '',
      },
      errorMessage: '',
    };
  },
  methods: {
    formatDateForApi(date: string) {
      const [day, month, year] = date.split('/');
      if (day && month && year) {
        return `${day}/${month}/${year}`;
      }
      return date;
    },

    async submitForm() {
      this.errors = { name: '', cpf: '', birthdate: '' };
      this.errorMessage = '';

      try {
        const token = localStorage.getItem('token');
        if (!token) {
          this.errorMessage = 'Token não encontrado';
          return;
        }

        const formattedBirthdate = this.formatDateForApi(this.form.birthdate);

        const response = await axios.put(
          `${import.meta.env.VITE_API_URL}/api/users/update`,
          {
            name: this.form.name,
            cpf: this.form.cpf,
            birthdate: formattedBirthdate,
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (response.status === 200) {
          this.errorMessage = '';
          alert(response.data.message);
          window.location.href = 'usuarios'
        }
      } catch (error) {
        if (error.response) {
          const status = error.response.status;

          if (status === 422) {
            this.errors = this.extractValidationErrors(error.response.data.errors);
          } else if (status === 409) {
            this.errorMessage = error.response.data.message;
          } else if (status === 500) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = 'Erro inesperado ao atualizar os dados.';
          }
        } else {
          this.errorMessage = 'Erro inesperado ao atualizar os dados.';
        }
      }
    },
    extractValidationErrors(errors: any) {
      let formattedErrors: any = {};
      for (let field in errors) {
        if (errors[field].length > 0) {
          formattedErrors[field] = errors[field][0];
        }
      }
      return formattedErrors;
    }
  },
};
</script>

<style scoped>
</style>
