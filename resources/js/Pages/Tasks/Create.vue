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
                        <h1 class="text-3xl font-bold text-gray-900">Nova Tarefa</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Crie uma nova tarefa para organizar seu trabalho
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form @submit.prevent="submitForm" class="p-6">
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Título *
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.title }"
                            placeholder="Digite o título da tarefa"
                        />
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600">
                            {{ errors.title }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.description }"
                            placeholder="Digite a descrição da tarefa (opcional)"
                        ></textarea>
                        <p v-if="errors.description" class="mt-1 text-sm text-red-600">
                            {{ errors.description }}
                        </p>
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria
                        </label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.category_id }"
                        >
                            <option value="">Selecione uma categoria (opcional)</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">
                            {{ errors.category_id }}
                        </p>
                    </div>

                    <!-- Priority -->
                    <div class="mb-6">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Prioridade
                        </label>
                        <select
                            id="priority"
                            v-model="form.priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.priority }"
                        >
                            <option value="">Selecione a prioridade</option>
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>
                        <p v-if="errors.priority" class="mt-1 text-sm text-red-600">
                            {{ errors.priority }}
                        </p>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-6">
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Data de Vencimento
                        </label>
                        <input
                            id="due_date"
                            v-model="form.due_date"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.due_date }"
                        />
                        <p v-if="errors.due_date" class="mt-1 text-sm text-red-600">
                            {{ errors.due_date }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input
                                id="is_completed"
                                v-model="form.is_completed"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label for="is_completed" class="ml-2 block text-sm text-gray-900">
                                Marcar como concluída
                            </label>
                        </div>
                        <p v-if="errors.is_completed" class="mt-1 text-sm text-red-600">
                            {{ errors.is_completed }}
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            href="/tasks"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                        >
                            <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ processing ? 'Criando...' : 'Criar Tarefa' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    errors: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const processing = ref(false);

const form = reactive({
    title: '',
    description: '',
    category_id: '',
    priority: '',
    due_date: '',
    is_completed: false
});

const submitForm = () => {
    processing.value = true;

    router.post('/tasks', form, {
        onSuccess: () => {
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        }
    });
};
</script>
