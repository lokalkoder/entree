<script setup>
import { computed } from 'vue';

const props = defineProps(['href', 'active']);

const classes = computed(() => props.active
    ? 'border-b-2 border-green-900 dark:border-gray-50'
    : 'border-transparent'
);
</script>

<template>
    <button type="button"
            class="inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 border-b-2 border-transparent text-green-900 focus:border-green-700 focus:outline-none dark:text-white dark:focus:border-gray-50 transition duration-150 ease-in-out "
            :class="classes">
        <slot />
        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"/>
        </svg>
    </button>
</template>

<style scoped>
button:focus > svg { transform: rotate(90deg) }
</style>
