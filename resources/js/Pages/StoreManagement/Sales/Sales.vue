<template>
    <AppLayout title="Vendas">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Total de <b>{{ sales.length }}</b> vendas</h1>
                <Link :href="route('sales.create')">
                    <PrimaryButton>
                        NOVA VENDA
                    </PrimaryButton>
                </Link>
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
                            Quantidade de produtos
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Valor Total
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Data
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        ></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="s,i in sales" :key="i">
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.id"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.qty"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="s.total_price"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="formatDate(s.created_at)"
                        ></td>
                        <td>
                            <div class="flex gap-4 justify-center items-center">
                                <button @click="getProductsFromSale(s)" title="Ver detalhes da venda">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                    </svg>
                                </button>
                                <Link :href="route('sales.edit',{id: s.id})" title="Editar">
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
    props: ['sales','productsHasSales'],
    data() {
        return {
            productsFromSale: []
        }
    },
    methods: {
        getProductsFromSale(productsHasSalesId){
            this.productsFromSale = this.productsHasSales.filter(x => x.products_has_sales_id == productsHasSalesId.id);
            alert('Produtos da venda: \n \n'+this.productsFromSale.map(x => x.name).join('\n'));
        },
        formatDate(date) {
            const data = new Date(date);

            const dia = ('0' + data.getDate()).slice(-2);
            const mes = ('0' + (data.getMonth() + 1)).slice(-2);
            const ano = data.getFullYear();
            const hora = ('0' + data.getHours()).slice(-2);
            const minuto = ('0' + data.getMinutes()).slice(-2);

            return `${dia}/${mes}/${ano} ${hora}:${minuto}`;
        },
    }
}
</script>

<style lang="scss" scoped>
    
</style>