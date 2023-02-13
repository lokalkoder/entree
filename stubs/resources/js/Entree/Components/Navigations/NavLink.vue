<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'border-b-2 border-green-900 dark:border-gray-50'
    : 'border-transparent'
);
</script>

<template>
    <Link :href="href"
          class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-green-900 border-transparent hover:text-green-700 hover:border-green-700 focus:outline-none dark:text-white dark:hover:border-gray-50 dark:focus:bg-gray-50 transition duration-150 ease-in-out"
          :class="classes">
        <slot />
    </Link>
</template>
