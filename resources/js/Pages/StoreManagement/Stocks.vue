<template>
    <AppLayout title="Estoque">
        <PageCard>
            <h1 class="text-2xl">Estoque contará com:</h1>
            <ul>
                <li>Produtos (quantidade em estoque)</li>
                <li>Entradas/compras (dos fornecedores)</li>
                <li>Categorias (dos produtos)</li>
            </ul>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
export default {
    components: {
        AppLayout,
        PageCard
    }
}
</script>

<style lang="scss" scoped>
    
</style>