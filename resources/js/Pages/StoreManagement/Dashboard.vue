<template>
    <AppLayout title="Vendas">
        <PageCard>
            <h1 class="text-2xl">Dashboard</h1>
            <br><hr><br>
            <div class="data-structure">
                <div class="left-content">
                    <div class="qty-elements">
                        <div>
                            <span>Produtos</span>
                            <span v-text="qtyProducts"></span>
                        </div>
                        <div>
                            <span>Vendas</span>
                            <span v-text="sales.length"></span>
                        </div>
                        <div>
                            <span>Clientes</span>
                            <span v-text="customers.length"></span>
                        </div>
                        <div>
                            <span>Funcionários</span>
                            <span v-text="employeers.length"></span>
                        </div>
                        <div>
                            <span>Fornecedores</span>
                            <span v-text="supplier.length"></span>
                        </div>
                        <div>
                            <span>Vendas todo período</span>
                            <span v-text="'R$ '+sumTotal(sales)"></span>
                        </div>
                        <div>
                            <span>Vendas do dia</span>
                            <span v-text="'R$ '+sumTotal(salesToday)"></span>
                        </div>
                    </div>
                    <div class="charts">
                        <div class="chart">
                            <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                        </div>
                    </div>
                </div>
                <div class="right-content">
                    <h3 v-if="salesToday.length > 0">Vendas do dia: <b>{{ salesToday.length }}</b></h3>
                    <h3 v-else>Nenhuma venda hoje</h3>
                    <table v-if="salesToday.length > 0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Qtd</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="s,i in salesToday" :key="i">
                                <td v-text="s.id"></td>
                                <td v-text="s.qty"></td>
                                <td v-text="'R$ '+formatValue(s.total_price)"></td>
                            </tr>
                        </tbody>
                    </table>
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
    props: ['productsInStock','qtyProducts','sales','customers','employeers','supplier','salesToday'],
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
    methods: {
        sumTotal(data){
            let total = 0
            data.forEach(e => {
                total += parseFloat(e.total_price)
            });
            return this.formatValue(total)
        },
        formatValue(data){
            const valorFormatado = parseFloat(data).toFixed(2).replace('.', ',')
            return valorFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        }
    }
}
</script>

<style lang="scss" scoped>
    .data-structure{
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: flex-start;
        .left-content{
            display: grid;
            gap: 2rem;
            grid-template-rows: 1fr auto;
            .qty-elements{
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 1fr;
                gap: 1rem;
                div{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    background-color: $purple2;
                    color: $white;
                    border-radius: 8px;
                    padding: .6rem;
                    min-width: 140px;
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
                gap: 4rem;
                .chart{
                    width: 400px;
                    height: 400px;
                }
            }
        }
        .right-content{
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border-radius: 6px;
            h3{
                font-size: 1.2rem;
                font-weight: bold;
                text-align: center;
                b{
                    color: $purple2;
                }
            }
            table{
                width: 100%;
                border-collapse: collapse;

                th, td{
                    padding: 8px;
                    text-align: center;
                }

                tr{
                    :first-child{
                        border-radius: 4px 0 0 4px;
                    }
                    :last-child{
                        border-radius: 0 4px 4px 0;
                    }
                    &:hover{
                        background-color: #f5f5f5;
                    }
                }

                thead{
                    tr{
                        th{
                            background-color: $purple2;
                            color: $white;
                        }
                    }
                }
            }
        }
    }
</style>