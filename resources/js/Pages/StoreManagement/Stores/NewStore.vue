<template>
    <AppLayout title="Nova Loja">
        <PageCard>
            <h1>Nova Loja</h1>
            <Link :href="route('stores.index')">
                Voltar
            </Link>
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