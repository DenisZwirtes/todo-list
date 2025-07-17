<template>
    <div class="relative">
        <InputLabel :for="id" :value="label" />

        <!-- Campo de entrada -->
        <div class="mt-1 relative">
            <div
                @click="toggleDropdown"
                class="min-h-[38px] w-full border border-gray-300 rounded-md shadow-sm bg-white cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                :class="{ 'ring-2 ring-indigo-500 border-indigo-500': isOpen }"
            >
                <!-- Tags dos itens selecionados -->
                <div class="flex flex-wrap gap-1 p-2">
                    <div
                        v-for="selectedItem in selectedItems"
                        :key="selectedItem.id"
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                    >
                        {{ selectedItem.name }}
                        <button
                            @click.stop="removeItem(selectedItem)"
                            class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:text-indigo-600 hover:bg-indigo-200"
                        >
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Placeholder quando não há itens selecionados -->
                    <span v-if="selectedItems.length === 0" class="text-gray-500">
                        {{ placeholder }}
                    </span>
                </div>
            </div>

            <!-- Ícone de dropdown -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Dropdown de opções -->
        <div
            v-if="isOpen"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
        >
            <div class="p-2">
                <input
                    v-model="searchTerm"
                    type="text"
                    placeholder="Buscar..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    @click.stop
                />
            </div>

            <div class="max-h-48 overflow-auto">
                <div
                    v-for="item in filteredItems"
                    :key="item.id"
                    @click="toggleItem(item)"
                    class="px-4 py-2 text-sm cursor-pointer hover:bg-indigo-50"
                    :class="{ 'bg-indigo-100': isSelected(item) }"
                >
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            :checked="isSelected(item)"
                            class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            @click.stop
                        />
                        {{ item.name }}
                    </div>
                </div>

                <div v-if="filteredItems.length === 0" class="px-4 py-2 text-sm text-gray-500">
                    Nenhum item encontrado
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    items: {
        type: Array,
        default: () => []
    },
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        default: 'Selecione os itens'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchTerm = ref('');

// Filtra os itens baseado no termo de busca
const filteredItems = computed(() => {
    if (!searchTerm.value) return props.items;
    return props.items.filter(item =>
        item.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

// Itens selecionados
const selectedItems = computed(() => {
    return props.items.filter(item =>
        props.modelValue.includes(item.id)
    );
});

// Verifica se um item está selecionado
const isSelected = (item) => {
    return props.modelValue.includes(item.id);
};

// Toggle do dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchTerm.value = '';
    }
};

// Adiciona ou remove um item
const toggleItem = (item) => {
    const newValue = [...props.modelValue];
    const index = newValue.indexOf(item.id);

    if (index > -1) {
        newValue.splice(index, 1);
    } else {
        newValue.push(item.id);
    }

    emit('update:modelValue', newValue);
};

// Remove um item diretamente
const removeItem = (item) => {
    const newValue = props.modelValue.filter(id => id !== item.id);
    emit('update:modelValue', newValue);
};

// Fecha o dropdown quando clicar fora
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
