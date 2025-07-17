<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Categorias</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Organize suas tarefas em categorias
                        </p>
                    </div>
                    <Link
                        href="/categories/create"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nova Categoria
                    </Link>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200"
                >
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div
                                    class="w-4 h-4 rounded-full mr-3"
                                    :style="{ backgroundColor: category.color }"
                                ></div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ category.name }}
                                </h3>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="`/categories/${category.id}`"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </Link>
                                <Link
                                    :href="`/categories/${category.id}/edit`"
                                    class="text-gray-600 hover:text-gray-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </Link>
                                <button
                                    @click="deleteCategory(category)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Tarefas:</span>
                                <span class="font-medium">{{ category.tasks_count || 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Criada em:</span>
                                <span>{{ formatDate(category.created_at) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <Link
                                :href="`/tasks?category_id=${category.id}`"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                Ver tarefas desta categoria →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="categories.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma categoria encontrada</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Comece criando sua primeira categoria para organizar suas tarefas.
                </p>
                <div class="mt-6">
                    <Link
                        href="/categories/create"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Criar Categoria
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    }
});

const deleteCategory = (category) => {
    if (confirm(`Tem certeza que deseja excluir a categoria "${category.name}"?`)) {
        router.delete(`/categories/${category.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                // Category deleted successfully
            }
        });
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('pt-BR');
};
</script>
