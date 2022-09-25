<template>
    <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-4 px-2 sm:px-6 lg:px-8 text-slate-900">
            <slot name="header" />
        </div>
    </header>
</template>

<script>
export default {
    name: "Header"
}
</script>

<style scoped>

</style>
