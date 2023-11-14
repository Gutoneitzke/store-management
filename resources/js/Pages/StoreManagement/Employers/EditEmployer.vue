<template>
    <AppLayout title="Novo Funcionário">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Editando Funcionário: {{ form.name }}</h1>
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
                                <option v-for="g,i in genders" :key="i" :value="g.value" v-text="g.label"></option>
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
                                @change="form.state = ''; form.city = ''"
                                id="country" 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
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
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option v-for="s,i in form.country.states" :key="i" :value="s" v-text="s.name"></option>
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
                                <option v-for="c,i in form.state.cities" :key="i" :value="c.id" v-text="c.name"></option>
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

                    <div :class="['mt-4 grid gap-4', form.password ? 'grid-cols-2' : '']">
                        <div>
                            <InputLabel for="password" value="Senha" />
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                            />
                        </div>

                        <div v-if="form.password">
                            <InputLabel for="password_confirmation" value="Confirmar Senha" />
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                            />
                            <span v-if="validPassword" class="invalid-field">As senhas devem ser iguais</span>
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
    props: ['employee','locales', 'stores', 'userStore'],
    data() {
        return {
            form: {
                name: this.employee.name,
                email: this.employee.email,
                gender: this.employee.gender,
                cpf: this.employee.cpf,
                type: 'EMPLOYEE',
                description: this.employee.description,
                country: '',
                state: '',
                city: this.employee.cities_id,
                password: this.employee.password,
                address_street: this.employee.address_street,
                address_neighborhood: this.employee.address_neighborhood,
                address_number: this.employee.address_number,
                address_complement: this.employee.address_complement,
                password: this.employee.password,
                password_confirmation: '',
                store_id: this.userStore.stores_id,
                user_original_store: this.userStore
            },
            genders: [
                {value: 'M', label: 'Masculino'},
                {value: 'F', label: 'Feminino'},
                {value: 'O', label: 'Outro'},
            ],
            processing: false,
            fieldsToValidate: ['name','email','cpf','type','gender','city','address_street','address_neighborhood','address_number', 'store_id']
        }
    },
    mounted(){
        let all = JSON.parse(JSON.stringify(Object.values(this.locales)));
        all.forEach(a => {
            Object.values(a.states).forEach(s => {
                s.cities.forEach(c => {
                    if(c.id == this.employee.cities_id){
                        this.form.country = a;
                        this.form.state = s;
                    }
                })
            })
        });
    },
    methods: {
        submit(){
            this.processing = true;
            const formState = this.isValidForm();
            if(formState){
                this.$inertia.put(route('employers.update', this.employee.id), this.form, {
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

            if(isValid && (!this.validPassword || !this.form.password)){
                return true;
            }
            this.processing = false;
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