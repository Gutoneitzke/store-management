<template>
    <AppLayout title="Vendas">
        <PageCard>
            <h1 class="text-2xl">Dashboard</h1>
            <br><hr><br>
            <div class="qty-elements">
                <div>
                    <span>Quantidade de produtos</span>
                    <span v-text="qtyProducts"></span>
                </div>
                <div>
                    <span>Quantidade de vendas</span>
                    <span v-text="qtySales"></span>
                </div>
                <div>
                    <span>Quantidade de clientes</span>
                    <span v-text="qtyCustomers"></span>
                </div>
                <div>
                    <span>Quantidade de funcionários</span>
                    <span v-text="qtyEmployeers"></span>
                </div>
                <div>
                    <span>Quantidade de fornecedores</span>
                    <span v-text="qtySupplier"></span>
                </div>
            </div>
            <div class="charts">
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
            </div>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
import BarChart from '@/Components/BarChart.vue'
export default {
    components: {
        AppLayout,
        PageCard,
        BarChart
    },
    props: ['productsInStock','qtyProducts','qtySales','qtyCustomers','qtyEmployeers','qtySupplier'],
    data() {
        return {
            charts: [
                {
                    slug: 'Relação de produtos no estoque',
                    labels: this.productsInStock.labels,
                    datasets: [{
                        label: 'Estoque de produtos',
                        backgroundColor: '#f87979',
                        data: this.productsInStock.values
                    }]
                }
            ]
        }
    },
}
</script>

<style lang="scss" scoped>
    .qty-elements{
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2rem;
        div{
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: $purple2;
            color: $white;
            border-radius: 8px;
            padding: .6rem;
            :first-child{
                font-size: .94rem;
            }
            :last-child{
                font-weight: bold;
                font-size: 1.4rem;
            }
        }
    }
    .charts{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        .chart{
            width: 400px;
            height: 400px;
        }
    }
</style>