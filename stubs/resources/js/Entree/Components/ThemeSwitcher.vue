<script setup>
import {computed, reactive} from 'vue';

let colorTheme = reactive({ dark: localStorage.getItem('color-theme') === 'dark' && window.matchMedia('(prefers-color-scheme: dark)').matches })

if (colorTheme.dark) {
    document.documentElement.classList.add('dark');
}

let colorThemeStorage = computed({
    // getter
    get() {
        return localStorage.getItem('color-theme')
    },
    // setter
    set(newValue) {
        localStorage.setItem('color-theme', newValue)
    }
})

const themeDark = () => {
    if (colorThemeStorage) {
        if (colorThemeStorage === 'light') {
            document.documentElement.classList.add('dark');
            colorThemeStorage =  'dark';
            colorTheme.dark = true
        } else {
            document.documentElement.classList.remove('dark');
            colorThemeStorage = 'light';
            colorTheme.dark = false
        }
        // if NOT set via local storage previously
    } else {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
            colorTheme.dark = false
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
            colorTheme.dark = true
        }
    }
}
</script>
<template>
    <div class="flex-initial mr-4">
        <button id="theme-toggle" type="button"
                @click="themeDark"
                class="text-gray-700 dark:text-white hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm py-2">
            <svg v-if="colorTheme.dark" id="theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
            <svg v-else id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
    </div>
</template>

<script>

export default {
  setup() {
    return {
    }
  },
}
</script>

<style>
</style>
