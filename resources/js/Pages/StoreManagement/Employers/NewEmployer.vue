<template>
    <AppLayout title="Novo Funcionário">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Novo Funcionário</h1>
                <Link :href="route('employers.index')">
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
                            <InputLabel for="cpf" value="CPF *" />
                            <TextInput
                                id="cpf"
                                v-model="form.cpf"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="cpf"
                            />
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 grid-cols-2">
                        <div>
                            <InputLabel for="email" value="Email *" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                                required
                                autocomplete="email"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="gender" value="Sexo *" />
                            <select 
                                v-model="form.gender" 
                                id="gender" 
                                required
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                                <option value="o">Outro</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 grid">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="store" value="Loja do Funcionário *" />
                            <select 
                                v-model="form.store_id" 
                                id="store" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option v-for="s,i in stores" :key="i" :value="s.id" v-text="s.name"></option>
                            </select>
                        </div>
                    </div>

                    <div :class="['mt-4 grid gap-4', form.country ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="country" value="País *" />
                            <select 
                                v-model="form.country" 
                                id="country" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option v-for="c,i in countries" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                        </div>

                        <div v-if="form.country" class="flex gap-1 flex-col">
                            <InputLabel for="state" value="Estado *" />
                            <select 
                                v-model="form.state" 
                                id="state" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option v-for="s,i in states" :key="i" :value="s.id" v-text="s.name"></option>
                            </select>
                        </div>
                    </div>

                    <div v-if="form.state" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="city" value="Cidade *" />
                            <select 
                                v-model="form.city" 
                                id="city" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option v-for="c,i in cities" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                        </div>

                        <div v-if="form.city">
                            <InputLabel for="address_street" value="Rua *" />
                            <TextInput
                                id="address_street"
                                v-model="form.address_street"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="address_street"
                            />
                        </div>
                    </div>

                    <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_neighborhood" value="Bairro *" />
                            <TextInput
                                id="address_neighborhood"
                                v-model="form.address_neighborhood"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="address_neighborhood"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="address_number" value="Número *" />
                            <TextInput
                                id="address_number"
                                v-model="form.address_number"
                                type="number"
                                class="mt-1 block w-full"
                                required
                                autocomplete="address_street"
                            />
                        </div>
                    </div>

                    <div v-if="form.city" class="mt-4 grid">
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

            <div class="mt-4 grid gap-4 grid-cols-2">
                <div>
                    <InputLabel for="password" value="Senha *" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirmar Senha *" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <span v-if="validPassword" class="invalid-field">As senhas devem ser iguais</span>
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
    props: ['countries', 'states', 'cities', 'stores'],
    data() {
        return {
            form: {
                name: '',
                cpf: '',
                type: 'EMPLOYEE',
                description: '',
                country: '',
                state: '',
                city: '',
                password: '',
                address_street: '',
                address_neighborhood: '',
                address_number: '',
                address_complement: '',
                password: '',
                password_confirmation: '',
                store_id: ''
            },
            processing: false,
            fieldsToValidate: ['name','email','password','cpf','type','gender','city','address_street','address_neighborhood','address_number', 'store_id']
        }
    },
    methods: {
        submit(){
            const formState = this.isValidForm();
            if(formState){
                this.processing = true;
                this.$inertia.post(route('employers.store'), this.form, {
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

            if(isValid && !this.validPassword){
                return true;
            }
            return false;
        }
    },
    computed: {
        validPassword(){
            return this.form.password != this.form.password_confirmation && !this.form.password_confirmation == false;
        }
    }
}
</script>

<style lang="scss" scoped>
    .invalid-field{
        font-size: 12px;
        color: red;
    }
</style>