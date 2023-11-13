<template>
    <AppLayout title="Novo Fornecedor">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Novo Fornecedor</h1>
                <Link :href="route('suppliers.index')">
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
                            <InputLabel for="cpf_cnpj" value="CPF ou CNPJ *" />
                            <TextInput
                                id="cpf_cnpj"
                                v-model="form.cpf_cnpj"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="cpf_cnpj"
                            />
                        </div>
                    </div>

                    <div class="mt-4 grid">
                        <InputLabel for="description" value="Descrição/Observação" />
                        <TextInput
                            id="description"
                            v-model="form.description"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                            autocomplete="description"
                        />
                    </div>

                    <div class="mt-4 grid gap-4 grid-cols-2">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="email" value="Email *" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="text"
                                class="mt-1 block w-full"
                                autofocus
                                required
                                autocomplete="email"
                            />
                        </div>

                        <div class="flex gap-2 flex-col">
                            <InputLabel for="store" value="Loja *" />
                            <select 
                                v-model="form.stores_id"
                                id="store" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in myStores" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                        </div>
                    </div>

                    <div :class="['mt-4 grid gap-4', form.country ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="country" value="País *" />
                            <select 
                                v-model="form.country"
                                @change="form.state = ''; form.city = ''"
                                id="country" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in locales" :key="i" :value="c" v-text="c.name"></option>
                            </select>
                        </div>

                        <div v-if="form.country" class="flex gap-1 flex-col">
                            <InputLabel for="state" value="Estado *" />
                            <select 
                                v-model="form.state"
                                @change="form.city = ''"
                                id="state" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in form.country.states" :key="i" :value="c" v-text="c.name"></option>
                            </select>
                        </div>
                    </div>

                    <div v-if="form.state" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="city" value="Cidade *" />
                            <select 
                                v-model="form.city" 
                                id="city" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in form.state.cities" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                        </div>

                        <div v-if="form.city">
                            <InputLabel for="phone" value="Telefone *" />
                            <TextInput
                                id="phone"
                                v-model="form.phone"
                                type="number"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="phone"
                            />
                        </div>
                    </div>

                    <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_street" value="Rua *" />
                            <TextInput
                                id="address_street"
                                v-model="form.address_street"
                                type="text"
                                required
                                class="mt-1 block w-full"
                                autocomplete="address_street"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <div class="flex gap-1 flex-col">
                                <InputLabel for="address_neighborhood" value="Bairro *" />
                                <TextInput
                                    id="address_neighborhood"
                                    v-model="form.address_neighborhood"
                                    type="text"
                                    required
                                    class="mt-1 block w-full"
                                    autocomplete="address_neighborhood"
                                />
                            </div>
                        </div>
                    </div>

                    <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_number" value="Número *" />
                            <TextInput
                                id="address_number"
                                v-model="form.address_number"
                                type="number"
                                required
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
                            Cadastrar
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
    props: ['locales','myStores'],
    data() {
        return {
            form: {
                name: '',
                description: '',
                phone: '',
                email: '',
                cpf_cnpj: '',
                country: '',
                state: '',
                city: '',
                address_street: '',
                address_neighborhood: '',
                address_number: '',
                address_complement: '',
                stores_id: ''
            },
            processing: false,
            fieldsToValidate: ['name','email','phone','cpf_cnpj','city','address_street','address_number','address_neighborhood','stores_id']
        }
    },
    methods: {
        submit(){
            this.processing = true;
            const formState = this.isValidForm();
            if(formState){
                this.$inertia.post(route('suppliers.store'), this.form, {
                    forceFormData: true,
                    onSuccess: (data) => {
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

            return isValid;
        }
    }
}
</script>