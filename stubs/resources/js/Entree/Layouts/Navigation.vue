<script setup>
import {ref} from 'vue';
import Logo from '@/Entree/Components/Logo.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Entree/Components/Navigations/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/inertia-vue3';
import ButtonLink from "@/Entree/Components/Navigations/ButtonLink.vue";
import ThemeSwitcher from "@/Entree/Components/ThemeSwitcher.vue";
import ResponsiveNavigation from "@/Entree/Layouts/ResponsiveNavigation.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="bg-green-100 dark:bg-gray-900 dark:text-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <Logo class="block h-9 w-auto bg-white p-1"/>
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
                        <theme-switcher />
                        <BreezeDropdown class="flex-1" align="right" width="52" content-classes="">
                            <template #trigger>
                                <span class="inline-flex rounded-md gap-2 cursor-pointer active:cursor-pointer">
                                    <img v-if="$page.props.auth.user.avatar" class="p-1 w-10 h-10 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" :src="$page.props.auth.user.avatar"  alt="Bordered avatar"/>
                                    <div v-else class="inline-flex overflow-hidden relative justify-center items-center w-10 h-10 bg-gray-100 rounded-full dark:bg-gray-600">
                                        <span class="font-medium text-gray-600 dark:text-gray-300">{{ $page.props.auth.user.initials }}</span>
                                    </div>
                                </span>
                            </template>

                            <template #content>
                                <div class="divide-y divide-gray-100 text-sm text-gray-900">
                                    <div class="py-3 px-4 bg-green-700 dark:bg-gray-700 text-gray-50">
                                        <div class="font-semibold">{{ $page.props.auth.user.name }}</div>
                                        <div class="text-xs text-left mt-2 font-sans truncate inline-flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-4 my-auto mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                            </svg>
                                            {{ $page.props.auth.user.email }}
                                        </div>
                                        <div class="hidden text-xs text-left mb-2 font-sans truncate inline-flex">
                                            Login {{ $page.props.auth.login }}
                                        </div>
                                    </div>
                                    <BreezeDropdownLink classes="bg-white border-2 border-l-green-900 inline-flex"
                                                        :href="route('user.account', $page.props.auth.user.id)"
                                                        method="get" as="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 my-auto mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Account
                                    </BreezeDropdownLink>
                                    <BreezeDropdownLink classes="bg-white border-2 border-l-green-900 inline-flex"
                                                        :href="route('logout')"
                                                        method="post" as="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 my-auto mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        Log Out
                                    </BreezeDropdownLink>
                                </div>
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
            <responsive-navigation />
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
