<template>
    <AppLayout title="Editar Loja">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Editando Loja: {{ store.name }}</h1>
                <Link :href="route('stores.index')">
                    Voltar
                </Link>
            </div>

            <div class="mt-8">
                <form @submit.prevent="submit">
                    <div class="mt-4 grid gap-4 grid-cols-2">
                        <div>
                            <InputLabel for="name" value="Nome *" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="name"
                            />
                        </div>

                        <div>
                            <InputLabel for="cnpj" value="CNPJ *" />
                            <TextInput
                                id="cnpj"
                                v-model="form.cnpj"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="cnpj"
                            />
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 grid-cols-2">
                        <div>
                            <InputLabel for="description" value="Descrição" />
                            <TextInput
                                id="description"
                                v-model="form.description"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="description"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="city" value="Cidade *" />
                            <select 
                                v-model="form.city" 
                                id="city" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in cities" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                        </div>
                    </div>

                    <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_street" value="Rua" />
                            <TextInput
                                id="address_street"
                                v-model="form.address_street"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="address_street"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <div class="flex gap-1 flex-col">
                                <InputLabel for="address_neighborhood" value="Bairro" />
                                <TextInput
                                    id="address_neighborhood"
                                    v-model="form.address_neighborhood"
                                    type="text"
                                    class="mt-1 block w-full"
                                    autocomplete="address_neighborhood"
                                />
                            </div>
                        </div>
                    </div>

                    <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_number" value="Número" />
                            <TextInput
                                id="address_number"
                                v-model="form.address_number"
                                type="number"
                                class="mt-1 block w-full"
                                autocomplete="address_street"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_complement" value="Complemento" />
                            <TextInput
                                id="address_complement"
                                v-model="form.address_complement"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="address_complement"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="mt-4" :class="{ 'opacity-25': processing }" :disabled="processing">
                            Editar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </PageCard>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageCard from '@/Components/PageCard.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        AppLayout,
        PageCard,
        Link,
        InputLabel,
        TextInput,
        PrimaryButton
    },
    props: ['cities','store'],
    data() {
        return {
            form: {
                name: this.store.name,
                cnpj: this.store.cnpj,
                description: this.store.description,
                city: this.store.cities_id,
                address_street: this.store.address_street,
                address_neighborhood: this.store.address_neighborhood,
                address_number: this.store.address_number,
                address_complement: this.store.address_complement,
            },
            processing: false,
            fieldsToValidate: ['name','cnpj','city']
        }
    },
    methods: {
        submit(){
            this.processing = true;
            const formState = this.isValidForm();
            if(formState){
                this.$inertia.put(route('stores.update', this.store.id), this.form, {
                    onSuccess: () => {
                        console.log('sucesso')
                    },
                    onError: (error) => {
                        console.log(error)
                    },
                    onFinish: () => {
                        this.processing = false;
                    }
                });
            }
        },
        isValidForm(){
            let isValid = true;

            for(const field of this.fieldsToValidate){
                if(!this.form[field]){
                    isValid = false;
                    break;
                }
            }

            if(isValid){
                return true;
            }
            return false;
        }
    }
}
</script>

<style lang="scss" scoped>
    
</style>