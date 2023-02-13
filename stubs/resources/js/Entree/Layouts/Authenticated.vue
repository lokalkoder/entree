<script setup>
import ApplicationNav from '@/Entree/Layouts/Navigation.vue';
import ApplicationHeader from '@/Entree/Layouts/Header.vue';
import ApplicationFooter from '@/Entree/Layouts/Footer.vue';
import MessageBox from "@/Entree/Components/Forms/MessageBox.vue";
import {Head, usePage} from '@inertiajs/inertia-vue3';
import AlertBox from "@/Entree/Components/Forms/AlertBox.vue";
import {computed, provide, reactive} from "vue";

defineProps({
    title: String,
})

</script>

<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-green-50 dark:bg-gray-300">
            <ApplicationNav/>

            <!-- Page Heading -->
            <ApplicationHeader v-if="title">
                <template #header>
                        {{ title }}
                </template>
            </ApplicationHeader>

            <!-- Page Content -->
            <main>
                <slot />
                <alert-box />
            </main>
        </div>
        <ApplicationFooter/>
        <message-box />
    </div>
</template>

<script>
export default {
    name: "Authenticated"
}
</script>
