<script setup>
import {reactive, ref} from 'vue';
import Logo from '@/Entree/Components/Logo.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Entree/Components/Navigations/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/inertia-vue3';
import ButtonLink from "@/Entree/Components/Navigations/ButtonLink.vue";

const showingNavigationDropdown = ref(false);

let colorTheme = reactive({ dark: localStorage.getItem('color-theme') === 'dark' && window.matchMedia('(prefers-color-scheme: dark)').matches })

const themeDark = () => {
    if (localStorage.getItem('color-theme')) {
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            colorTheme.dark = true
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            colorTheme.dark = false
        }
        // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            colorTheme.dark = false
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            colorTheme.dark = true
        }
    }
}
</script>

<template>
    <nav class="bg-gray-100 dark:bg-gray-900 dark:text-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <Logo class="block h-9 w-auto bg-white p-2 rounded-full"/>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div v-for="(menu, index) in $page.props.menu"
                         class="hidden sm:flex sm:items-center sm:ml-6"
                         key="index">
                        <NavLink v-if="menu.sub_menus === null" :href="menu.url"
                                 :active="route().current(menu.route)">
                            {{ menu.label }}
                        </NavLink>
                        <BreezeDropdown v-else align="left" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button-link>
                                        {{ menu.label }}
                                    </button-link>
                                </span>
                            </template>

                            <template #content>
                                <div v-for="(submenu, index) in menu.sub_menus">
                                    <BreezeDropdownLink :href="submenu.url"
                                                        :active="route().current(submenu.route)">
                                        {{ submenu.label }}
                                    </BreezeDropdownLink>
                                </div>
                            </template>
                        </BreezeDropdown>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="flex ml-3 relative">
                        <div class="flex-initial mr-4">
                            <button id="theme-toggle" type="button"
                                    @click="themeDark"
                                    class="text-gray-700 dark:text-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm py-2">
                                <svg v-if="colorTheme.dark" id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>
                                <svg v-else id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <BreezeDropdown class="flex-1" align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = ! showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                            <path
                                :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <BreezeResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Dashboard
                </BreezeResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Log Out
                    </BreezeResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "Navigation",
    computed: {

    }
}
</script>

<style scoped>

</style>
