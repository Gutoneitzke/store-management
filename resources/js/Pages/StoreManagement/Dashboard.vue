<template>
    <AppLayout title="Vendas">
        <PageCard>
            <h1 class="text-2xl">Dashboard</h1>
            <br>
            <hr>
            <br>
            <div class="charts">
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
                <div class="chart">
                    <BarChart :labels="charts[0].labels" :datasets="charts[0].datasets" />
                </div>
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
    data() {
        return {
            charts: [
                {
                    slug: 'qty_sales_last_weeks',
                    labels: [ 'January', 'February', 'March' ],
                    datasets: [{
                        label: 'Data One',
                        backgroundColor: '#f87979',
                        data: [40, 20, 12]
                    }]
                }
            ]
        }
    },
}
</script>

<style lang="scss" scoped>
    .charts{
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        .chart{
            width: 300px;
            height: 300px;
        }
    }
</style>