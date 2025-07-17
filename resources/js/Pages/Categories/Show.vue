<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center">
          <Link
            href="/categories"
            class="mr-4 text-gray-400 hover:text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
          </Link>
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Detalhes da Categoria</h1>
            <p class="mt-2 text-sm text-gray-600">
              Veja as informações completas da categoria
            </p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow rounded-lg p-6">
        <div v-if="category">
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Nome</span>
            <span class="text-lg text-gray-900 font-semibold">{{ category.name }}</span>
          </div>
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Cor</span>
            <span>
              <span class="inline-block w-6 h-6 rounded-full align-middle mr-2" :style="`background:${category.color}`"></span>
              {{ category.color }}
            </span>
          </div>
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Criada em</span>
            <span>{{ formatDate(category.created_at) }}</span>
          </div>
        </div>
        <div v-else>
          <p>Categoria não encontrada.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const category = page.props.category;

function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-BR');
}
</script>
