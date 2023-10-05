<template>
    <Head :title="title" />
    <div class="dashboard">
        <div class="content">
            <Header />
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
import Header from '@/Components/Layouts/HeaderDashboard.vue';
import { Head } from '@inertiajs/inertia-vue3';
export default {
    components: {
        Header,
        Head
    },
    props: {
        title: {
            type: String,
            required: false,
            default: ''
        }
    }
}
</script>

<style lang="scss" scoped>
    .dashboard{
        background-color: $dark4;
        color: $white;
        display: grid;
        grid-template-columns: auto 1fr;
        min-height: 100vh;
        .content{
            main{
                padding: 0 4rem;
            }
        }
    }
    @media screen and (max-width: 900px) {
        .content {
            width: 100vw !important;
        }
    }
    @media screen and (max-width: 500px) {
        .content {
            main{
                padding: 1rem !important;
            }
        }
    }
</style>