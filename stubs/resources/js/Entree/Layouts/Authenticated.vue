<script setup>
import ApplicationNav from '@/Entree/Layouts/Navigation.vue';
import ApplicationHeader from '@/Entree/Layouts/Header.vue';
import ApplicationFooter from '@/Entree/Layouts/Footer.vue';
import MessageBox from "@/Entree/Components/Forms/MessageBox.vue";
import { Head } from '@inertiajs/inertia-vue3';
import AlertBox from "@/Entree/Components/Forms/AlertBox.vue";

defineProps({
    title: String,
})

</script>

<template>
    <div>
        <Head :title="title" />
        <div class="min-h-screen bg-gray-100">
            <ApplicationNav/>

            <!-- Page Heading -->
            <ApplicationHeader>
                <template #header>
                        {{ title }}
                </template>
            </ApplicationHeader>

            <!-- Page Content -->
            <main>
                <slot />
                <message-box />
                <alert-box />
            </main>
        </div>
        <ApplicationFooter/>
    </div>
</template>

<script>
export default {
    name: "Authenticated"
}
</script>
