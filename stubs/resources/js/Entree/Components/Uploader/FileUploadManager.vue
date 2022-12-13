<script setup>
import SectionDivider from "@/Components/SectionDivider.vue";
import {inject, onMounted, reactive, ref, watchEffect} from "vue";
import Uploader from "@/Entree/Components/Uploader.vue";
import Swal from "sweetalert2";

const webhook = inject('$webhook')

const props = defineProps({
    authorize: {
        type: Boolean,
        default: true
    },
    model:{
        type: String,
        default: null
    },
    id: {
        type: [Number, String],
        default: null
    },
    title: {
        type: String,
        default: 'File Upload'
    },
});

const emit = defineEmits(['fileUploaded', 'fileRemoved'])

let attachment = reactive({ list:[] });

let form = reactive({
    id : null,
    model : null,
})

watchEffect( async () => {
    form.id = parseInt(props.id);
    form.model = props.model;

    if (form.id && form.model) {
        attachment.list = await webhook.search('api.attachment.list', form)
    }
})

const hasUploaded = file => {
    attachment.list = file[0]

    emit('fileUploaded', attachment.list)
}


const editName = async media => {
    const { value: filename } = await Swal.fire({
        title: 'Change file name',
        input: 'text',
        inputPlaceholder: 'Enter file name',
        confirmButtonColor: '#1f2937',
    })

    attachment.list =  await webhook.patch('api.attachment.update', media.uuid, {name: filename})
}

const url = ref(route('api.attachment.upload'))

const removeUpload = async media => {
    attachment.list =  await webhook.delete('api.attachment.delete', media.uuid)

    emit('fileUploaded', attachment.list)
}

</script>
<template>
    <div class="w-full p-2 mt-6 border-2 border-gray-900 dark:border-gray-50">
        <SectionDivider>
            <div class="w-full flex">
                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>{{ title }}</span>
            </div>
        </SectionDivider>
        <div class="">
            <div v-if="authorize" class="flex flex-row-reverse mr-2">
                <button class="button button-primary">
                    <Uploader class="peer p-0"
                              :url="url"
                              :form="form"
                              @file-uploaded="hasUploaded">
                        <div class="inline-flex overflow-hidden p-2 relative justify-center items-center bg-gray-100 text-gray-600 dark:bg-gray-50 hover:bg-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                    </Uploader>
                </button>
            </div>
            <div class="grid grid-cols-4 place-items-stretch mt-4 bg-gray-100">
                <template  v-for="(media, uuid) in attachment.list" :key="uuid">
                    <div class="flex content-center items-center group p-2 dark:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="flex-initial w-6 h-6 mr-2">
                            <path strokse-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <div class="flex-none w-3/4 text-sm hover:underline overflow-y-hidden overflow-y-ellipsis">
                            {{ media.name }}
                        </div>
                        <div class="group-hover:flex-none group-hover:w-1/4 hidden group-hover:inline-flex">
                            <a target="_blank" :href="media.original_url"
                               class="ml-2 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor"
                                     class="w-5 h-5 text-indigo-500 hover:rounded-full hover:bg-indigo-500 hover:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                            <div class="group/edit rounded-full hover:ring-0.5 p-0.5 hover:ring-blue-500 hover:bg-blue-500 flex items-center"
                                 @click="editName(media)">
                                <div class="flex items-center ring-1 p-1 ring-blue-500 group-hover/edit:ring-white rounded-full text-center text-blue-500 group-hover/edit:rounded-full group-hover/edit:bg-blue-500 group-hover/edit:text-white cursor-pointer">
                                    <svg v-if="authorize" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor"
                                         class="w-2 h-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </div>
                            </div>
                            <svg v-if="authorize"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor"
                                 @click="removeUpload(media)"
                                 class="w-5 h-5 text-red-500 hover:rounded-full hover:bg-red-500 hover:text-white cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "FileUploadManager"
}
</script>

<style scoped>

</style>
