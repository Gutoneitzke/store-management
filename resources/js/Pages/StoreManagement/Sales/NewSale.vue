<template>
    <AppLayout title="Nova Venda">
        <PageCard>
            <div class="flex gp-2 items-center justify-between">
                <h1 class="text-2xl">Nova Saída de Produto(s)</h1>
                <Link :href="route('sales.index')">
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
                            <div class="flex gap-2 flex-col">
                                <InputLabel for="customers" value="Cliente *" />
                                <select 
                                    v-model="form.customers_id"
                                    v-if="getAccordingSelectedStore(customers).length > 0"
                                    id="customers" 
                                    required 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option v-for="c,i in getAccordingSelectedStore(customers)" :key="i" :value="c.id" v-text="c.name"></option>
                                </select>
                                <p v-else class="not-has-data">Nenhum fornecedor nessa loja! Crie <Link :href="route('customers.index')"><u>aqui</u></Link></p>
                            </div>
                        </div>
                    </div>

                    <div v-if="form.stores_id" class="mt-4 grid gap-4 grid-cols-2">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="description" value="Description" />
                            <TextInput
                                id="description"
                                v-model="form.description"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="description"
                            />
                        </div>

                        <div class="flex gap-2 flex-col">
                            <InputLabel for="type_sell" value="Tipo da saída *" />
                            <select 
                                v-model="form.type_sell"
                                id="type_sell" 
                                required 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            >
                                <option v-for="c,i in typeSell" :key="i" :value="c.value" v-text="c.text"></option>
                            </select>
                        </div>
                    </div>

                    <div v-if="form.stores_id" v-for="p,i in form.productsToSell" :key="i" class="mt-8 product">
                        <div class="flex items-center justify-end mt-4">
                            <button v-if="i > 0" @click="form.productsToSell.splice(i, 1);">Remover produto</button>
                        </div>
                        <div class="mt-4 grid gap-4 grid-cols-3">
                            <div class="flex gap-1 flex-col">
                                <InputLabel for="product" :value="'Produto '+ (i+1) +'*'" />
                                <select 
                                    v-model="p.products_id"
                                    v-if="getAccordingSelectedStore(products).length > 0"
                                    id="product" 
                                    required 
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                    @change="getUnityPrice(p)"
                                >
                                    <option v-for="c,i in getAccordingSelectedStore(products)" :key="i" :value="c.id" v-text="c.name"></option>
                                </select>
                                <p v-else class="not-has-data">Nenhum produto nessa loja! Crie <Link :href="route('stocks.index')"><u>aqui</u></Link></p>
                            </div>

                            <div class="flex gap-1 flex-col">
                                <InputLabel for="qty" value="Quantidade *" />
                                <TextInput
                                    id="qty"
                                    v-model="p.qty"
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
                                    v-model="p.unity_price"
                                    type="text"
                                    class="disabled mt-1 block w-full"
                                    required
                                    disabled
                                    autocomplete="unity_price"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton v-if="form.productsToSell[0]['products_id']" @click="addMoreProductsToSell()">
                            + Adicionar Produto na venda
                        </PrimaryButton>
                    </div>

                    <div v-if="form.productsToSell[0].unity_price != '0'" :class="['mt-4 grid gap-4', form.discount != '0' && !form.discount == false ? 'grid-cols-3' : 'grid-cols-2']">
                        <div class="flex gap-1 flex-col">
                            <InputLabel for="discount" value="Porcentagem de desconto  *" />
                            <TextInput
                                id="discount"
                                v-model="form.discount"
                                type="number"
                                class="mt-1 block w-full"
                                autocomplete="discount"
                            />
                        </div>

                        <div class="flex gap-1 flex-col">
                            <InputLabel for="total_price" value="Valor final original da venda" />
                            <TextInput
                                id="total_price"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="total_price"
                                disabled
                                :value="'R$ '+formatValue(getTotalFinalToSell)"
                            />
                        </div>

                        <div v-if="form.discount != '0' && !form.discount == false" class="flex gap-1 flex-col">
                            <InputLabel for="total_price" value="Valor final da venda com desconto" />
                            <TextInput
                                id="total_price"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="total_price"
                                disabled
                                :value="'R$ '+formatValue(getTotalFinalToSellWithDiscount)"
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
    props: ['products','productsHasEntries','productsHasCategories','categories','customers','myStores'],
    data() {
        return {
            form: {
                stores_id: '',
                type_sell: 'SELL',
                customers_id: '',
                description: '',
                productsToSell: [{
                    products_id: '',
                    qty: '1',
                    unity_price: '0',
                }],
                discount: '0',
            },
            typeSell: [
                {value: 'SELL',  text: 'Venda'},
                {value: 'OTHER', text: 'Outro (perda, doação, ...)'},
            ],
            processing: false,
            fieldsToValidate: ['stores_id','type_sell','customers_id','productsToSell']
        }
    },
    methods: {
        submit(){
            this.processing = true;
            const formState = this.isValidForm();
            if(formState){
                this.$inertia.post(route('sales.store'), this.form, {
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
                if(field == 'productsToSell'){
                    this.form[field].forEach(e => {
                        if(!e.products_id || !e.qty || e.qty == 0 || !e.unity_price || e.unity_price == 0){
                            isValid = false;
                        }
                    })
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
            return data.filter(x => x.stores_id == this.form.stores_id);
        },
        resetAll(){
            this.form.type_sell = 'SELL';
            this.form.customers_id = '';
            this.form.description = '';
            this.form.productsToSell = [{
                products_id: '',
                qty: '1',
                unity_price: '0',
            }];
            this.form.discount = '0';
        },
        formatValue(data){
            const valorFormatado = parseFloat(data).toFixed(2).replace('.', ',');
            return valorFormatado.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },
        addMoreProductsToSell(){
            this.form.productsToSell.push({
                    products_id: '',
                    qty: '1',
                    unity_price: '0',
                });
        },
        getUnityPrice(data){
            const up = this.productsHasEntries.find(x => x.products_id == data.products_id).unity_price;
            data.unity_price = up;
        }
    },
    computed: {
        getTotalFinalToSell(){
            let sum = 0;
            this.form.productsToSell.forEach(e => {
                sum += (parseFloat(e.unity_price) * parseFloat(e.qty))
            });
            return sum;
        },
        getTotalFinalToSellWithDiscount(){
            let sum = this.getTotalFinalToSell;
            return sum - (sum * this.form.discount)/100;
        }
    }
}
</script>

<style lang="scss" scoped>
    .disabled{
        opacity: 0.5;
        cursor: not-allowed;
    }
    .product{
        background-color: $white2;
        padding: .4rem 2rem 1.4rem;
        border-radius: 1rem;
    }
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