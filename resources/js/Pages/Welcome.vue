<script setup>
    import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />
    <h1>Welcome to Store Management</h1>
    <br><br>
    <Link :href="route('login')">Log in</Link>
    <br>
    <Link :href="route('register')">Register</Link>
</template>
