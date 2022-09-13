<template>
    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
                Log Out
            </BreezeResponsiveNavLink>
        </div>
    </div>
</template>

<script>
export default {
    name: "ResponsiveNavigation"
}
</script>

<style scoped>

</style>
