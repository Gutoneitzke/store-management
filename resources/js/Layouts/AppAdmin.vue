<template>
    <Head :title="title" />
    <Header />
    <section>
        <slot></slot>
    </section>
</template>

<script>
import Header from '@/Components/Layouts/HeaderAdmin.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
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
</style>