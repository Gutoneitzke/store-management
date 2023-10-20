<template>
    <AppLayout title="Lojas">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Lojas</h1>
                <Link :href="route('stores.create')">
                    Nova Loja
                </Link>
            </div>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        AppLayout,
        PageCard,
        Link
    }
}
</script>

<style lang="scss" scoped>
    
</style>