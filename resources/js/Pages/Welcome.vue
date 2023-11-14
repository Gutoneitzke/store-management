<script setup>
    import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Bem-vindo" />
    <div class="welcome">
        <h1>Seja Bem-vindo ao Store Management</h1>
        <p>Aqui você poderá realizar o controle da sua loja</p>
        <div class="box-login-register">
            <Link class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-4" :href="route('register')">Cadastrar</Link>
            <Link class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-4" :href="route('login')">Entrar</Link>
        </div>
    </div>
</template>

<style lang="scss">
    .welcome{
        background-color: #F3F4F6;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        text-align: center;
        gap: 4rem;
        height: 100vh;
        h1{
            font-size: 2rem;
        }
        .box-login-register{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
        }
    }
</style>

