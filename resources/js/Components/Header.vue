<template>
    <header>
        <div class="header-left">
            <Link :href="route('home')">
                <img src="../../../../public/images/quanticocap-logo.png" alt="">
            </Link>
        </div>
        <ul class="header-right">
            <li>
                <Link :href="route('login')" v-text="'Entrar'"></Link>
            </li>
            <li>
                <Link :href="route('register')" v-text="'Alo'"></Link>
            </li>
        </ul>
    </header>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
export default {
    components:{
        Link
    },
}
</script>

<style lang="scss" scoped>
    header{
        background-color: $dark;
        color: $white;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        height: 8vh;
        padding: 0 5rem;
        .header-left{
            img{
                width: 80px;
            }
        }
        .header-center{
            width: 50%;
            ul{
                display: flex;
                flex-wrap: wrap;
                justify-content: space-evenly;
            }
        }
        .header-right{
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
    }
</style>