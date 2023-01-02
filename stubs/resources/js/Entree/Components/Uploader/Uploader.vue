<script setup>
import DropZone from "@/Entree/Components/Uploader/DropZone.vue";
import {inject, onMounted, onUpdated, reactive, ref, watch, watchEffect} from 'vue';
import {useForm, usePage} from "@inertiajs/inertia-vue3";

const webhook = inject('$webhook')

const props = defineProps({
    id: {
        type: Number,
        default: (new Date()).getTime(),
    },
    url: {
        type: String,
        default: null,
    },
    form: {
        type: [Array, Object],
        default: [],
    },
});

const emit = defineEmits(['fileUploaded'])

const files = ref([])
const fileUploader = ref({ form: props.form})

const addFiles = newFiles => {
    let newUploadableFiles = [...newFiles].map(file => new UploadableFile(file)).filter(file => !fileExists(file.id))
    files.value = files.value.concat(newUploadableFiles)
}

const fileExists = otherId => files.value.some(({id}) => id === otherId)

const onInputChange = async e => {
    addFiles(e.target.files)
    e.target.value = null // reset so that selecting the same file again will still cause it to fire this change

    let response = await uploadFiles(files.value)

    emit('fileUploaded', response)

    files.value = []
}

const uploadFile = async file => {
    // set up the request data

    let formData = new FormData()
    formData.append('file', file.file)

    for ( const item in props.form) {
        formData.append(item, props.form[item])
    }

    file.status = 'loading'

    let param = {
        method: 'POST',
        body: formData
    };

    let request = Object.assign(param, webhook.headerBearer())

    let response = await fetch(
        props.url,
        request
    ).then(response => {
        const data = response.json()
        file.status = data.ok
        return data
    }).catch(error => {
        if (Object.keys(error).length > 0) {
            usePage().props.value.errors = error.response.data.errors
            usePage().props.value.messages.error = error.response.data.message

            webhook.swalToaster()
        }
    });

    // change status to indicate the success of the upload request
    return response
}

const uploadFiles = files => Promise.all(files.map(file => uploadFile(file)))


class UploadableFile {
    constructor(file) {
        this.file = file
        this.id = `${file.name}-${file.size}-${file.lastModified}-${file.type}`
        this.url = URL.createObjectURL(file)
        this.status = null
    }
}

defineExpose({ fileUploader })

</script>
<template>
    <div class="w-full">
        <DropZone class="w-fit drop-area" @files-dropped="addFiles" #default="{ dropZoneActive }">
            <label :for="'file-input' + props.id" class="cursor-pointer">
                <input type="file" :id="'file-input' + props.id" class="hidden opacity-0 appearance-none" @click="onInputClick"  @change="onInputChange">
                <slot>
                    <div class="group w-64 py-6 px-4 text-center bg-gray-50  border-gray-100 border-2 border-dotted text-center">
                        <div class="group-hover:hidden" v-if="dropZoneActive">
                            <div class="text-sm">Drop Them Here</div>
                            <span class="smaller">to add them</span>
                        </div>
                        <div class="group-hover:hidden" v-else>
                            <div class="text-sm">Drag Your Files Here</div>
                            <div class="text-sm">
                                or <strong><em>click here</em></strong> to select files
                            </div>
                        </div>
                        <div class="hidden group-hover:block mx-auto items-stretch">
                            <div class="text-xs text-gray-50">Click here to select files</div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor"
                                 class="mx-auto h-6 w-6 transition-all duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                    </div>
                </slot>
            </label>
        </DropZone>
    </div>
</template>

<script>
export default {
    name: "Uploader"
}
</script>

<style scoped>

</style>
