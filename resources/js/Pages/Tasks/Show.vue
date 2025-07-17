<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center">
          <Link
            href="/tasks"
            class="mr-4 text-gray-400 hover:text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
          </Link>
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Detalhes da Tarefa</h1>
            <p class="mt-2 text-sm text-gray-600">
              Veja as informações completas da tarefa
            </p>
          </div>
        </div>
      </div>
      <div class="bg-white shadow rounded-lg p-6">
        <div v-if="task">
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Título</span>
            <span class="text-lg text-gray-900 font-semibold">{{ task.title }}</span>
          </div>
          <div v-if="task.description" class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Descrição</span>
            <span class="text-gray-800">{{ task.description }}</span>
          </div>
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Status</span>
            <span :class="task.is_completed ? 'text-green-700' : 'text-yellow-700'">
              {{ task.is_completed ? 'Concluída' : 'Pendente' }}
            </span>
          </div>
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Prioridade</span>
            <span>{{ task.priority || 'Não definida' }}</span>
          </div>
          <div class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Criada em</span>
            <span>{{ formatDate(task.created_at) }}</span>
          </div>
          <div v-if="task.category" class="mb-4">
            <span class="block text-sm font-medium text-gray-700">Categoria</span>
            <span>{{ task.category.name }}</span>
          </div>
        </div>
        <div v-else>
          <p>Tarefa não encontrada.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const task = page.props.task;

function formatDate(date) {
  return new Date(date).toLocaleDateString('pt-BR');
}
</script>
