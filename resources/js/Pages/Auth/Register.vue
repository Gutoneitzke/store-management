<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    countries: Array,
    states: Array,
    cities: Array,
});

const form = useForm({
    name: '',
    email: '',
    cpf: '',
    gender: '',
    country: '',
    state: '',
    city: '',
    address_street: '',
    address_neighborhood: '',
    address_number: '',
    address_complement: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const countries = props.countries
const states = props.states

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Cadastro" />

    <AuthenticationCard>
        <template #logo>
            Logo
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nome" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4 grid gap-4 grid-cols-2">
                <div>
                    <InputLabel for="cpf" value="CPF" />
                    <TextInput
                        id="cpf"
                        v-model="form.cpf"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.cpf" />
                </div>

                <div class="flex gap-1 flex-col">
                    <InputLabel for="gender" value="Sexo" />
                    <select v-model="form.gender" id="gender" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                        <option value="o">Outro</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.country" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div :class="['mt-4 grid gap-4', form.country ? 'grid-cols-2' : '']">
                <div class="flex gap-1 flex-col">
                    <InputLabel for="country" value="País" />
                    <select v-model="form.country" id="country" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="c,i in countries" :key="i" :value="c.id" v-text="c.name"></option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.country" />
                </div>

                <div v-if="form.country" class="flex gap-1 flex-col">
                    <InputLabel for="state" value="Estado" />
                    <select v-model="form.state" id="state" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="s,i in states" :key="i" :value="s.id" v-text="s.name"></option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.state" />
                </div>
            </div>

            <div v-if="form.state" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                <div class="flex gap-1 flex-col">
                    <InputLabel for="city" value="Cidade" />
                    <select v-model="form.city" id="city" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="c,i in cities" :key="i" :value="c.id" v-text="c.name"></option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.city" />
                </div>

                <div v-if="form.city" class="flex gap-1 flex-col">
                    <InputLabel for="address_street" value="Rua" />
                    <TextInput
                        id="address_street"
                        v-model="form.address_street"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="address_street"
                    />
                    <InputError class="mt-2" :message="form.errors.address_street" />
                </div>
            </div>

            <div v-if="form.city" :class="['mt-4 grid gap-4', form.city ? 'grid-cols-2' : '']">
                <div class="flex gap-1 flex-col">
                    <InputLabel for="address_neighborhood" value="Bairro" />
                    <TextInput
                        id="address_neighborhood"
                        v-model="form.address_neighborhood"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="address_neighborhood"
                    />
                    <InputError class="mt-2" :message="form.errors.address_neighborhood" />
                </div>

                <div class="flex gap-1 flex-col">
                    <InputLabel for="address_number" value="Número" />
                    <TextInput
                        id="address_number"
                        v-model="form.address_number"
                        type="number"
                        class="mt-1 block w-full"
                        required
                        autocomplete="address_street"
                    />
                    <InputError class="mt-2" :message="form.errors.address_number" />
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
                        required
                        autocomplete="address_complement"
                    />
                    <InputError class="mt-2" :message="form.errors.address_complement" />
                </div>
            </div>

            <div class="mt-4 grid gap-4 grid-cols-2">
                <div>
                    <InputLabel for="password" value="Senha" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirmar Senha" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Já tem conta ?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Cadastrar
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
