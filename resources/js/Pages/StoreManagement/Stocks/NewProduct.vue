<template>
    <AppLayout title="Novo Produto">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Nova Entrada de Produto</h1>
                <Link :href="route('stocks.index')">
                    Voltar
                </Link>
            </div>

            <div class="mt-8">
                <form @submit.prevent="submit">
                    <div :class="['mt-4 grid gap-4', form.stores_id ? 'grid-cols-2' : '']">
                        <div class="grid gap-1">
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

                        <div v-if="form.stores_id" class="flex gap-1 flex-col">
                            <InputLabel for="category" value="Categoria *" />
                            <select 
                                v-if="getAccordingSelectedStore(categories).length > 0"
                                v-model="form.category_id"
                                id="category" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in getAccordingSelectedStore(categories)" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                            <p v-else class="not-has-data">Nenhuma categoria nessa loja! Crie <Link :href="route('categories.index')"><u>aqui</u></Link></p>
                        </div>
                    </div>

                    <div v-if="form.category_id" class="mt-4 grid gap-4 form.stores_id grid-cols-2">
                        <div v-if="!form.newProduct.status">
                            <InputLabel for="product" value="Produto *" />
                            <select 
                                v-model="form.newProduct.product_id"
                                v-if="getAccordingSelectedStore(products).length > 0"
                                id="product" 
                                required 
                                class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            >
                                <option v-for="c,i in getAccordingSelectedStore(products)" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                            <button @click="form.newProduct.status = true">Produto não existente ?</button>
                        </div>
                            <!-- Adicionar mais produtos para um produto já existente -->
                        <div v-else>
                            <InputLabel for="name" value="Nome *" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="name"
                            />
                            <button v-if="products.length > 0" @click="form.newProduct.status = false">Produto já existente ?</button>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description *" />
                            <TextInput
                                id="description"
                                v-model="form.description"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="description"
                            />
                        </div>
                    </div>

                    <div v-if="form.category_id" class="mt-4 grid gap-4 grid-cols-3">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="qty" value="Quantidade *" />
                            <TextInput
                                id="qty"
                                v-model="form.qty"
                                type="number"
                                class="mt-1 block w-full"
                                required
                                autocomplete="qty"
                                @input="onlyIntegerNumber()"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="unity_price" value="Valor unitário *" />
                            <TextInput
                                id="unity_price"
                                v-model="form.unity_price"
                                type="number"
                                class="mt-1 block w-full"
                                required
                                autocomplete="unity_price"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="total_price" value="Valor total" />
                            <TextInput
                                id="total_price"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="total_price"
                                disabled
                                :value="'R$ '+form.qty*form.unity_price"
                            />
                        </div>
                    </div>

                    <div v-if="form.category_id" class="mt-4 grid gap-4 grid-cols-2">
                        <div class="flex gap-2 flex-col">
                            <InputLabel for="suppliers" value="Fornecedor *" />
                            <select 
                                v-model="form.suppliers_id"
                                v-if="getAccordingSelectedStore(suppliers).length > 0"
                                id="suppliers" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in getAccordingSelectedStore(suppliers)" :key="i" :value="c.id" v-text="c.name"></option>
                            </select>
                            <p v-else class="not-has-data">Nenhum fornecedor nessa loja! Crie <Link :href="route('suppliers.index')"><u>aqui</u></Link></p>
                        </div>

                        <div class="flex gap-2 flex-col">
                            <InputLabel for="type_entrie" value="Tipo da entrada *" />
                            <select 
                                v-model="form.type_entrie"
                                id="type_entrie" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="c,i in typeEntries" :key="i" :value="c.value" v-text="c.text"></option>
                            </select>
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
    props: ['products','categories','suppliers','myStores'],
    data() {
        return {
            form: {
                name: '',
                description: '',
                qty: '0',
                unity_price: '0',
                stores_id: '',
                category_id: '',
                type_entrie: 'BUY',
                suppliers_id: '',
                newProduct: {
                    status: true,
                    product_id: ''
                }
            },
            typeEntries: [
                {value: 'BUY',   text: 'Compra'},
                {value: 'OTHER', text: 'Outro (doação, ...)'},
            ],
            processing: false,
            fieldsToValidate: ['name','qty','unity_price','stores_id','category_id','type_entrie','suppliers_id']
        }
    },
    methods: {
        submit(){
            this.processing = true;
            const formState = this.isValidForm();
            if(formState){
                this.$inertia.post(route('stocks.store'), this.form, {
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
            } else {
                alert('Campos inválidos!');
                this.processing = false;
            }
        },
        isValidForm(){
            let isValid = true;

            for(const field of this.fieldsToValidate){
                if(field == 'name' && !this.form.newProduct.status){
                    continue;
                }
                if(!this.form[field] || this.form[field] == 0){
                    isValid = false;
                    break;
                }
            }

            return isValid;
        },
        onlyIntegerNumber(){
            this.form.qty = this.form.qty.replace(/[^0-9]/g, '');
        },
        getAccordingSelectedStore(data){
            return data.filter(x => x.stores_id == this.form.stores_id)
        }
    },
}
</script>

<style lang="scss" scoped>
    #total_price{
        cursor: not-allowed;
        opacity: 0.7;
        background-color: $green2;
    }
    .not-has-data{
        margin-top: .6rem;
        color: $red;
    }
</style>