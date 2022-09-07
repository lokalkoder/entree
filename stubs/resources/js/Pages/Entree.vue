<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Logo from '@/Entree/Components/Logo.vue';
import Login from "@/Entree/Components/Forms/Login.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="container px-6 mx-auto">
            <div
                class="flex flex-col text-center md:text-left md:flex-row h-screen justify-evenly md:items-center"
            >
                <div class="hidden sm:flex sm:flex-col md:w-7/12">
                    <div class="p-2">
                        <div class="w-9/12 p-4 flex">
                            <Logo class="text-xl h-16 p-3 flex-initial bg-white shadow-xl rounded-xl "/>
                            <h1 class="text-5xl my-auto text-white font-bold flex-1 ml-4">
                                Expert Dot MY
                            </h1>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="w-5/12 mx-auto md:mx-0 text-white">
                            Control and monitorize your website data from dashboard.
                        </p>
                    </div>
                </div>
                <div class="w-full lg:w-5/12 mx-auto md:mx-0">
                    <div class="bg-white p-10 flex flex-col w-full shadow-xl rounded-xl">
                        <login/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
