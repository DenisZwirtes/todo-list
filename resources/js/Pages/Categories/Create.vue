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
                        <h1 class="text-3xl font-bold text-gray-900">Nova Categoria</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Crie uma nova categoria para organizar suas tarefas
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form @submit.prevent="submitForm" class="p-6">
                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome *
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            :class="{ 'border-red-500': errors.name }"
                            placeholder="Digite o nome da categoria"
                        />
                        <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                            {{ errors.name }}
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
                            placeholder="Digite a descrição da categoria (opcional)"
                        ></textarea>
                        <p v-if="errors.description" class="mt-1 text-sm text-red-600">
                            {{ errors.description }}
                        </p>
                    </div>

                    <!-- Color -->
                    <div class="mb-6">
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                            Cor
                        </label>
                        <input
                            id="color"
                            v-model="form.color"
                            type="color"
                            class="w-16 h-8 p-0 border-0 rounded"
                        />
                        <p v-if="errors.color" class="mt-1 text-sm text-red-600">
                            {{ errors.color }}
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            href="/categories"
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
                            {{ processing ? 'Criando...' : 'Criar Categoria' }}
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
    }
});

const processing = ref(false);

const colors = [
    '#3B82F6', // blue
    '#10B981', // green
    '#F59E0B', // yellow
    '#EF4444', // red
    '#8B5CF6', // purple
    '#F97316', // orange
    '#06B6D4', // cyan
    '#84CC16', // lime
    '#EC4899', // pink
    '#6B7280', // gray
    '#1F2937', // dark gray
    '#DC2626', // dark red
];

const form = reactive({
    name: '',
    description: '',
    color: '#000000'
});

const submitForm = () => {
    processing.value = true;

    router.post('/categories', form, {
        onSuccess: () => {
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        }
    });
};
</script>
