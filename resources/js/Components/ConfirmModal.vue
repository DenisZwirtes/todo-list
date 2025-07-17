<template>
  <transition name="fade">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">
          <slot name="title">Tem certeza?</slot>
        </h2>
        <div class="mb-6">
          <slot name="message">Deseja realmente realizar esta ação?</slot>
        </div>
        <div class="flex justify-end gap-2">
          <button @click="$emit('cancel')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancelar</button>
          <button @click="$emit('confirm')" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Confirmar</button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
