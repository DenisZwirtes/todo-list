<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-indigo-50 via-white to-purple-100 py-8 px-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center animate-fade-in">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 mb-6 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Criar uma nova conta</h2>
            <p class="text-gray-500 text-sm mb-6 text-center">Preencha os campos para se cadastrar no sistema.</p>
            <form class="w-full space-y-4" @submit.prevent="submit">
                <div>
                    <input
                        id="name"
                        v-model="form.name"
                        name="name"
                        type="text"
                        required
                        autocomplete="name"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition placeholder-gray-400 text-gray-900 bg-gray-50 focus:bg-white shadow-sm"
                        :class="{ 'border-red-500': errors.name }"
                        placeholder="Nome completo"
                    />
                    <span v-if="errors.name" class="text-xs text-red-500 mt-1 block">{{ errors.name }}</span>
                </div>
                <div>
                    <input
                        id="email"
                        v-model="form.email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition placeholder-gray-400 text-gray-900 bg-gray-50 focus:bg-white shadow-sm"
                        :class="{ 'border-red-500': errors.email }"
                        placeholder="Email"
                    />
                    <span v-if="errors.email" class="text-xs text-red-500 mt-1 block">{{ errors.email }}</span>
                </div>
                <div class="relative">
                    <input
                        id="password"
                        v-model="form.password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition placeholder-gray-400 text-gray-900 bg-gray-50 focus:bg-white shadow-sm"
                        :class="{ 'border-red-500': errors.password }"
                        placeholder="Senha"
                    />
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-indigo-500"
                        @click="showPassword = !showPassword"
                        tabindex="-1"
                    >
                        <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.873 6.876A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 4.412M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                    <span v-if="errors.password" class="text-xs text-red-500 mt-1 block">{{ errors.password }}</span>
                </div>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        name="password_confirmation"
                        :type="showPasswordConfirmation ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition placeholder-gray-400 text-gray-900 bg-gray-50 focus:bg-white shadow-sm"
                        :class="{ 'border-red-500': errors.password_confirmation }"
                        placeholder="Confirmar senha"
                    />
                    <button
                        type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-indigo-500"
                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                        tabindex="-1"
                    >
                        <svg v-if="showPasswordConfirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95M6.873 6.876A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 4.412M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                    <span v-if="errors.password_confirmation" class="text-xs text-red-500 mt-1 block">{{ errors.password_confirmation }}</span>
                </div>
                <button
                    type="submit"
                    :disabled="processing"
                    class="w-full py-3 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold shadow-md hover:from-indigo-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition disabled:opacity-60 flex items-center justify-center gap-2"
                >
                    <svg v-if="processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ processing ? 'Registrando...' : 'Registrar' }}
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="/login" class="text-indigo-600 hover:underline text-sm transition">Já tem uma conta? <span class="font-semibold">Faça login</span></a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    errors: {
        type: Object,
        default: () => ({})
    }
});

const processing = ref(false);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const submit = () => {
    processing.value = true;

    router.post('/register', form, {
        onSuccess: () => {
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        }
    });
};
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: none; }
}
.animate-fade-in {
  animation: fade-in 0.7s cubic-bezier(.4,0,.2,1);
}
</style>
