<template>
    <header>
        <div class="header-left">
            <Link :href="route('admin.index')">
                <img src="../../../../public/images/quanticocap-logo.png" alt="">
            </Link>
        </div>
        <nav class="header-center">
            <ul>
                <li>
                    <Link :href="route('admin.index')">
                        Dashboard
                    </Link>
                </li>
            </ul>
        </nav>
        <div class="header-right">
            <Link
                :href="route('admin.logout')"
                method="get"
                as="a"
            >
                Sair
            </Link>
        </div>
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
                width: 50px;
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