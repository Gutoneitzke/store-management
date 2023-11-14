<template>
    <AppLayout title="Categorias">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Minhas categorias</h1>
                <div class="flex items-center gap-4">
                    <Link :href="route('stocks.index')">
                        Voltar
                    </Link>
                    <Link :href="route('categories.create')">
                        <PrimaryButton>
                            NOVA CATEGORIA
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
            <table class="table-auto w-full mt-8 divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Id
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Nome
                        </th>
                        <th 
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Descrição
                        </th>
                        <th
                            class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                        ></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="c,i in categories" :key="i">
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="c.id"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="c.name"
                        ></td>
                        <td 
                            class="px-6 py-4 whitespace-no-wrap" 
                            v-text="c.description"
                        ></td>
                        <td>
                            <div class="flex gap-4 justify-center">
                                <Link :href="route('categories.edit',{id: c.id})" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        AppLayout,
        PageCard,
        PrimaryButton,
        Link
    },
    props: ['categories']
}
</script>

<style lang="scss" scoped>
    
</style>