<template>
    <AppLayout title="Editar Produto">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Editando Produto: {{ stocks.name }}</h1>
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
                                @change="resetAll()"
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
                        <div>
                            <InputLabel for="name" value="Nome *" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autocomplete="name"
                            />
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
                            <InputLabel for="qty" value="Quantidade" />
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

                    <div v-if="form.unity_price != '0'" class="mt-4 grid gap-4 grid-cols-2">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="win_percentage" value="Porcentagem de ganho/lucro  *" />
                            <TextInput
                                id="win_percentage"
                                v-model="form.win_percentage"
                                type="number"
                                class="mt-1 block w-full"
                                required
                                autocomplete="win_percentage"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="total_price" value="Possível ganho total em cima do(s) produto(s)" />
                            <TextInput
                                id="total_price"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="total_price"
                                disabled
                                :value="'R$ '+getTotalWinWithPercentage"
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
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            >
                                <option v-for="c,i in typeEntries" :key="i" :value="c.value" v-text="c.text"></option>
                            </select>
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
    props: ['stocks','products','categories','suppliers','myStores'],
    data() {
        return {
            form: {
                name: this.stocks.name,
                description: this.stocks.description,
                qty: this.stocks.qty_stock.toString(),
                unity_price: this.stocks.unity_price,
                stores_id: this.stocks.stores_id,
                category_id: this.stocks.categories_id,
                type_entrie: this.stocks.type,
                win_percentage: this.stocks.win_percentage,
                suppliers_id: this.stocks.supplier_id,
                stocks_data: this.stocks
            },
            productsAccordingStoreAndCategory: [],
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
                this.$inertia.put(route('stocks.update', this.stocks.id), this.form, {
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
            return data.filter(x => x.stores_id == this.form.stores_id);
        },
        resetAll(){
            this.form.name = '';
            this.form.description = '';
            this.form.qty = '0';
            this.form.unity_price = '0';
            this.form.category_id = '';
            this.form.type_entrie = 'BUY';
            this.form.suppliers_id = '';
            this.form.newProduct = {
                status: true,
                product_id: ''
            };
        }
    },
    computed: {
        getTotalWinWithPercentage(){
            let totalOut = this.form.qty * this.form.unity_price;
            let percentageWin = (totalOut * this.form.win_percentage) /100;
            return totalOut + (percentageWin - totalOut);
        }
    }
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