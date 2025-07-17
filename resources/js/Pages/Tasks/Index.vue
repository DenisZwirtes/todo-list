<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tarefas</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Gerencie suas tarefas e organize seu trabalho
                        </p>
                    </div>
                    <Link
                        href="/tasks/create"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Tarefa
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Buscar
                        </label>
                        <input
                            id="search"
                            v-model="filters.search"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Buscar tarefas..."
                        />
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                            Categoria
                        </label>
                        <select
                            id="category"
                            v-model="filters.category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todas as categorias</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>
                        <select
                            id="status"
                            v-model="filters.is_completed"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todos os status</option>
                            <option value="0">Pendente</option>
                            <option value="1">Concluída</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tasks List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    <li v-for="task in tasks" :key="task.id" class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <input
                                        type="checkbox"
                                        :checked="task.is_completed"
                                        @change="toggleTask(task)"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                            :class="{ 'line-through text-gray-500': task.is_completed }"
                                        >
                                            {{ task.title }}
                                        </h3>
                                        <span
                                            v-if="task.priority"
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-red-100 text-red-800': task.priority === 'high',
                                                'bg-yellow-100 text-yellow-800': task.priority === 'medium',
                                                'bg-green-100 text-green-800': task.priority === 'low'
                                            }"
                                        >
                                            {{ task.priority }}
                                        </span>
                                    </div>
                                    <p v-if="task.description" class="text-sm text-gray-600 mt-1">
                                        {{ task.description }}
                                    </p>
                                    <div class="flex items-center mt-2 text-sm text-gray-500">
                                        <span v-if="task.category" class="mr-4">
                                            📁 {{ task.category.name }}
                                        </span>
                                        <span v-if="task.due_date" class="mr-4">
                                            📅 {{ formatDate(task.due_date) }}
                                        </span>
                                        <span>
                                            Criada em {{ formatDate(task.created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/tasks/${task.id}`"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </Link>
                                <Link
                                    :href="`/tasks/${task.id}/edit`"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </Link>
                                <button
                                    @click="confirmarExclusao(task)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Empty State -->
                <div v-if="tasks.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma tarefa encontrada</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Comece criando sua primeira tarefa.
                    </p>
                    <div class="mt-6">
                        <Link
                            href="/tasks/create"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Criar Tarefa
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <ConfirmModal
        :model-value="showDeleteModal"
        @cancel="cancelarExclusao"
        @confirm="excluirTarefa"
    >
        <template #title>Confirmar exclusão</template>
        <template #message>
            Tem certeza que deseja excluir a tarefa <span class="font-bold">{{ taskToDelete?.title }}</span>?
        </template>
    </ConfirmModal>
    <AlertMessage
        v-if="page.props.flash?.success && showSuccess"
        :message="page.props.flash.success"
        type="success"
        v-model="showSuccess"
    />
    <AlertMessage
        v-if="page.props.flash?.error && showError"
        :message="page.props.flash.error"
        type="error"
        v-model="showError"
    />
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { route } from 'ziggy-js';
import AlertMessage from '@/Components/AlertMessage.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const showDeleteModal = ref(false);
const taskToDelete = ref(null);

function confirmarExclusao(task) {
  taskToDelete.value = task;
  showDeleteModal.value = true;
}

function cancelarExclusao() {
  showDeleteModal.value = false;
  taskToDelete.value = null;
}

function excluirTarefa() {
  if (taskToDelete.value) {
    router.delete(route('tasks.destroy', taskToDelete.value.id), {
      onFinish: () => {
        showDeleteModal.value = false;
        taskToDelete.value = null;
      }
    });
  }
}

const props = defineProps({
    tasks: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const filters = reactive({
    search: props.filters.search || '',
    category_id: props.filters.category_id || '',
    is_completed: props.filters.is_completed || ''
});

const applyFilters = () => {
    router.get('/tasks', filters, {
        preserveState: true,
        replace: true
    });
};

const toggleTask = (task) => {
    router.put(`/tasks/${task.id}/toggle`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Task updated successfully
        }
    });
};

const deleteTask = (task) => {
    if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
        router.delete(`/tasks/${task.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                // Task deleted successfully
            }
        });
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
};

const page = usePage();
const showSuccess = ref(!!page.props.flash?.success);
const showError = ref(!!page.props.flash?.error);

watch(() => page.props.flash?.success, (val) => {
  showSuccess.value = !!val;
});
watch(() => page.props.flash?.error, (val) => {
  showError.value = !!val;
});
</script>
