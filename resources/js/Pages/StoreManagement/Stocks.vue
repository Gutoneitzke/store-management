<template>
    <AppLayout title="Estoque">
        <h1>Estoque contará com:</h1>
        <ul>
            <li>Produtos (quantidade em estoque)</li>
            <li>Entradas/compras (dos fornecedores)</li>
            <li>Categorias (dos produtos)</li>
        </ul>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout
    }
}
</script>

<style lang="scss" scoped>
    
</style>