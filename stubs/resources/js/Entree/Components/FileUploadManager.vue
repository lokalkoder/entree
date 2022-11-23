<script setup>
import {computed, inject, reactive} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

const webhook = inject('$webhook')

const props = defineProps({
    upload:{
        type: String,
        default: '/upload'
    },
    authorize: {
        type: String,
        default: ''
    }
});

const canUpload = computed(() => webhook.can(props.authorize,'edit'));

const setting = reactive({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Authorization: `Bearer ${usePage().props.value.auth.sanctum}`,
    },
    baseUrl: props.upload,
    windowsConfig: 1,
})

</script>
<template>
    <div class="p-1 hover:shadow">
        <div class="flex items-center space-x-2 font-semibold base-text-color uppercase leading-8 mb-3">
            <span class="">
                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </span>
            <span>File Upload</span>
        </div>
        <div class="px-2 mt-2 place-self-stretch" >
            <file-manager :settings="setting"></file-manager>
        </div>
    </div>
</template>

<script>
export default {
    name: "FileManager"
}
</script>

<style scoped>

</style>
