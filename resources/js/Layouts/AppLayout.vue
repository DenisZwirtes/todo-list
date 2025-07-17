<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link href="/" class="text-xl font-bold text-gray-900">
                                Todo List
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link
                                href="/"
                                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{ 'border-indigo-500 text-gray-900': $page.url === '/' }"
                            >
                                Dashboard
                            </Link>
                            <Link
                                href="/tasks"
                                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{ 'border-indigo-500 text-gray-900': $page.url.startsWith('/tasks') }"
                            >
                                Tarefas
                            </Link>
                            <Link
                                href="/categories"
                                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{ 'border-indigo-500 text-gray-900': $page.url.startsWith('/categories') }"
                            >
                                Categorias
                            </Link>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div class="ml-3 relative" ref="profileDropdownRef">
                            <div>
                                <button
                                    @click="profileDropdownOpen = !profileDropdownOpen"
                                    class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-700">
                                            {{ $page.props.auth?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                        </span>
                                    </div>
                                </button>
                            </div>

                            <!-- Profile dropdown menu -->
                            <div
                                v-show="profileDropdownOpen"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu-button"
                                tabindex="-1"
                            >
                                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                    <div class="font-medium">{{ $page.props.auth?.user?.name || 'Usuário' }}</div>
                                    <div class="text-gray-500">{{ $page.props.auth?.user?.email || 'email@example.com' }}</div>
                                </div>

                                <Link
                                    href="/logout"
                                    method="post"
                                    as="button"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    Sair
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        >
                            <span class="sr-only">Open main menu</span>
                            <svg
                                class="h-6 w-6"
                                :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg
                                class="h-6 w-6"
                                :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div
                v-show="mobileMenuOpen"
                class="sm:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <Link
                        href="/"
                        class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{ 'bg-indigo-50 border-indigo-500 text-indigo-700': $page.url === '/' }"
                    >
                        Dashboard
                    </Link>
                    <Link
                        href="/tasks"
                        class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{ 'bg-indigo-50 border-indigo-500 text-indigo-700': $page.url.startsWith('/tasks') }"
                    >
                        Tarefas
                    </Link>
                    <Link
                        href="/categories"
                        class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{ 'bg-indigo-50 border-indigo-500 text-indigo-700': $page.url.startsWith('/categories') }"
                    >
                        Categorias
                    </Link>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-indigo-700">
                                    {{ $page.props.auth?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ $page.props.auth?.user?.name || 'Usuário' }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth?.user?.email || 'email@example.com' }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        >
                            Sair
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const $page = usePage();
const profileDropdownOpen = ref(false);
const mobileMenuOpen = ref(false);
const profileDropdownRef = ref(null);

// Fecha dropdowns ao clicar fora
function handleClickOutside(event) {
    if (profileDropdownOpen.value && profileDropdownRef.value && !profileDropdownRef.value.contains(event.target)) {
        profileDropdownOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});
onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Fecha dropdowns ao trocar de rota
const closeDropdowns = () => {
    profileDropdownOpen.value = false;
    mobileMenuOpen.value = false;
};

watch(() => $page.url, () => {
    closeDropdowns();
});
</script>
