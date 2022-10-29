<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import Checkbox from "@/Components/Checkbox.vue";
import {inject} from "vue";

const webhook = inject('$webhook')

const props = defineProps({
    permissions: {
        type: [Array, Object],
        default: [],
    },
    assigned: {
        type: [Array, Object],
        default: [],
    },
    model: {
        type: String,
        default: null,
    },
    id: {
        type: [String, Number],
        default: null,
    }
})

const form = useForm({
    model_type: props.model,
    model_id: props.id,
    permission: props.assigned,
});

const savePermission = async () => {
    form.permission = await webhook.patch('api.user.permission', form.model_id, form);
}

</script>
<template>
    <section class="shadow">
        <div class="tabs">
            <template v-for="(permission, index) in permissions" :key="index" >
                <div class="flex flex-wrap justify-start overflow-hidden bg-gray-900 text-white border-b-2">
                    <label class="grow px-4 py-3 font-medium cursor-pointer" :for="permission.title">
                        {{ permission.title }}
                    </label>
                    <input class="peer mx-4 my-3 h-0 w-0 appearance-none rounded border text-slate-800 accent-slate-600 opacity-0" type="checkbox" :name="permission.title" :id="permission.title" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-4 my-3 h-6 w-6 transition-all duration-200 peer-checked:rotate-45">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    <div class="w-full -transparent absolute -translate-y-full scale-100 scale-y-0 opacity-0 transition-all duration-200 peer-checked:block peer-checked:relative peer-checked:translate-y-0  peer-checked:scale-100 peer-checked:scale-y-100 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:opacity-100 peer-checked:grid peer-checked:grid-cols-4 peer-checked:gap-4">
                            <template v-for="(item, index) in permission.item" :key="index" >
                                <div class="grow w-fit px-4 py-3 ">
                                    <label class="items-center mr-2.5">
                                        <div class="font-medium">
                                            <Checkbox :value="item.id" v-model:checked="form.permission" @change="savePermission"/>
                                            {{ item.desc }}
                                        </div>
                                        <span class="text-xs italic">{{ item.id }}</span>
                                    </label>
                                </div>
                            </template>
                    </div>
                </div>
<!--                <div class="border-l-2 border-transparent relative">-->
<!--                    <input class="w-full absolute z-10 cursor-pointer opacity-0 h-9 top-1" type="checkbox" :id="index">-->
<!--                    <header class="flex justify-between items-center py-2 pl-8 pr-8 cursor-pointer select-none tab-label" :for="index">-->
<!--                                <span class="text-grey-darkest font-thin text-xl">-->
<!--                                    {{ permission.title }}-->
<!--                                </span>-->
<!--                        <div class="rounded-full border border-grey w-7 h-7 flex items-center justify-center test">-->
<!--                            &lt;!&ndash; icon by feathericons.com &ndash;&gt;-->
<!--                            <svg aria-hidden="true" class="" data-reactid="266" fill="none" height="24" stroke="#606F7B" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">-->
<!--                                <polyline points="6 9 12 15 18 9">-->
<!--                                </polyline>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </header>-->
<!--                    <div class="tab-content">-->
<!--                        <div class="contrast-base-color pl-8 pr-8 py-2 grid grid-cols-1  md:grid-flow-row md:grid-cols-3">-->
<!--                            <div v-for="(item, index) in permission.item" :key="index" class="text-sm mt-1">-->
<!--&lt;!&ndash;                                <label class="flex items-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <t-checkbox v-model="form.permission" :value="item.id" />&ndash;&gt;-->
<!--&lt;!&ndash;                                    <span class="ml-2 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="font-medium">{{ item.desc }}</div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="text-xs italic ">{{ item.id }}</div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                </label>&ndash;&gt;-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </template>
        </div>
    </section>
</template>

<script>
export default {
    name: "Permission"
}
</script>

<style scoped>

</style>
