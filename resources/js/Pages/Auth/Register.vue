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
});

const form = useForm({
    name: '',
    email: '',
    cpf: '',
    country: '',
    state: '',
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

            <div class="mt-4">
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
