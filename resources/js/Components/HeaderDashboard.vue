<template>
    <header>
        <div class="header-left">
            <button v-if="showButtonHamburguer" @click="openSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
            <Link :href="route('contracts.index')">
                <img src="../../../../public/images/quanticocap-logo.png" alt="">
            </Link>
        </div>
        <div class="header-right">
            <p v-text="$t('qc-hi-welcome-to')+' QuânticoCap'"></p>
        </div>
    </header>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import emitter from '@/Components/Services/emitter.js';
export default {
    components:{
        Link
    },
    data() {
        return {
            mobileData: true,
            bluetooth: false,
            showSidebarStatus: false,
            showButtonHamburguer: false,
        }
    },
    created() { 
        emitter.on("show-button-hamburguer", data => {
            this.showButtonHamburguer = data;
        });
        if($(window).width() <= 1280)
        {
            this.showButtonHamburguer = true;
        }
    },
    methods: {
        openSidebar(){
            this.showSidebarStatus = true;
            emitter.emit("toggle-sidebar", this.showSidebarStatus);
        }
    }
}
</script>

<style lang="scss" scoped>
    header{
        background-color: $dark4;
        color: $white;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        padding: 2rem 4rem;
        .header-left{
            display: flex;
            align-items: center;
            gap: 2rem;
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
    @media screen and (max-width: 500px) {
        header{
            padding: 1rem !important;
        }
    }
</style>