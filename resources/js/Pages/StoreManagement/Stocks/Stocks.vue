<template>
    <AppLayout title="Estoque">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Meu estoque</h1>
                <div class="flex gap-4">
                    <Link :href="route('categories.index')">
                        <PrimaryButton>
                            CATEGORIAS
                        </PrimaryButton>
                    </Link>
                    <Link :href="route('suppliers.index')">
                        <PrimaryButton>
                            FORNECEDORES
                        </PrimaryButton>
                    </Link>
                    <Link :href="route('stocks.create')">
                        <PrimaryButton>
                            NOVO PRODUTO
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
            <table class="table-auto w-full mt-8 divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Id
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Nome
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Preço Total
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                            style="text-align: center;"
                        >
                            Quantidade em estoque
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Código do produto
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        ></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="s,i in products" :key="i">
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.id"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.name"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="'R$ '+formatValue(s.total_price)"
                        ></td>
                        <td 
                            class="qty_stock px-6 py-4 whitespace-no-wrap" 
                        >
                            <span v-text="s.qty_stock"></span>
                        </td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.code"
                        ></td>
                        <td>
                            <div class="flex gap-4 justify-center">
                                <Link :href="route('stocks.edit',{id: s.id})" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        AppLayout,
        PageCard,
        PrimaryButton,
        Link
    },
    props: ['products'],
    methods: {
        formatValue(data){
            const valorFormatado = parseFloat(data).toFixed(2).replace('.', ',');
            return valorFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}
</script>

<style lang="scss" scoped>
    .qty_stock{
        text-align: center;
        span{
            background-color: $green;
            color: $white;
            padding: .2rem 1.4rem;
            border-radius: 10px;
        }
    }
</style>