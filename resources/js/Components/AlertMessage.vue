<template>
  <transition name="fade">
    <div v-if="visible" :class="alertClass" class="fixed top-6 right-6 z-50 px-4 py-3 rounded shadow-lg flex items-center gap-2 min-w-[220px]">
      <span v-if="type === 'success'">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
      </span>
      <span v-else-if="type === 'error'">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </span>
      <span v-else>
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>
      </span>
      <span class="flex-1">{{ message }}</span>
      <button @click="close" class="ml-2 text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
      </button>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
  message: { type: String, required: true },
  type: { type: String, default: 'success' }, // 'success', 'error', 'info'
  duration: { type: Number, default: 3500 }, // ms
  modelValue: { type: Boolean, default: true },
});

const emit = defineEmits(['update:modelValue']);
const visible = ref(props.modelValue);

watch(() => props.modelValue, (val) => {
  visible.value = val;
});

watch(visible, (val) => {
  emit('update:modelValue', val);
});

watch(visible, (val) => {
  if (val && props.duration > 0) {
    setTimeout(() => visible.value = false, props.duration);
  }
});

const alertClass = computed(() => {
  switch (props.type) {
    case 'success': return 'bg-green-100 text-green-800 border border-green-300';
    case 'error': return 'bg-red-100 text-red-800 border border-red-300';
    case 'info':
    default: return 'bg-blue-100 text-blue-800 border border-blue-300';
  }
});

function close() {
  visible.value = false;
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
