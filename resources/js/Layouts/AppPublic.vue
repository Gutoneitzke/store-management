<template>
    <Header />
    <section>
        <slot></slot>
    </section>
</template>

<script>
import Header from '@/Components/Layouts/Header.vue'
export default {
    components: {
        Header
    }
}
</script>

<style lang="scss" scoped>
</style>