<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.PurchaseInvoice')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPurchaseInvoice'}">{{$t('global.PurchaseInvoice')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading" />
                        <div class="card-body">
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexPurchaseInvoice'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['store_id']">{{ errors['store_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['is_received']">{{ errors['is_received'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['supplier_id']">{{ errors['supplier_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['note']">{{ errors['note'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['price']">{{ errors['price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.quantity']">{{ errors['product.0.quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.sub_quantity']">{{ errors['product.0.sub_quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price']">{{ errors['product.0.price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.production_date']">{{ errors['product.0.production_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.expiry_date']">{{ errors['product.0.expiry_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.count_unit']">{{ errors['product.0.count_unitv'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.InvoiceResourceType') }}</label>

                                                <select v-model="data.type_invoice" :class="['form-select',{'is-invalid':v$.type_invoice.$error,'is-valid':!v$.type_invoice.$invalid}]">
                                                    <option value="0">{{$t('global.client')}}</option>
                                                    <option value="1">{{$t('global.Supplier')}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.type_invoice.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.ChooseStore') }}</label>

                                                <select v-model="data.store_id" :class="['form-select',{'is-invalid':v$.store_id.$error,'is-valid':!v$.store_id.$invalid}]">
                                                    <option v-for="store in stores" :kay="store.id" :value="store.id">{{store.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.store_id.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3 position-relative">
                                                <label> {{data.type_invoice == 1 ? $t('global.ChooseSupplier') : $t('global.ChooseClient')}} </label>
                                                <label class="balance"> {{$t('global.Balance')}} : {{balance}}</label>

                                                <input type="text"
                                                       class="form-control mb-1 input-Sender"
                                                       v-model="search"
                                                       @input="searchSender"
                                                       @focus="searchSender"
                                                       @blur="isButton = true"
                                                       autofocus
                                                       :placeholder="$t('treasury.Search')"
                                                >
                                                <ul class="dropdown-search sender-search" v-if="dropDownSenders.length">
                                                    <li
                                                        class="Sender"
                                                        v-for="(dropDownSender,index) in dropDownSenders"
                                                        :key="index"
                                                        @mouseenter="senderHover"
                                                        @click="showSenderName(index)"
                                                    >
                                                        {{data.type_invoice == 1 ? dropDownSender.name_supplier : dropDownSender.name }}
                                                    </li>
                                                </ul>

                                                <input type="text"
                                                       :class="['form-control',{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}]"
                                                       disabled
                                                       v-model="nameSender"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.supplier_id.required.$invalid">{{$t('global.supplierIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.product" class="col-md-12 mb-12 body-account row">

                                                           <div class="col-md-3 mb-3 position-relative">
                                                               <label>{{ $t('global.Products') }}</label>

                                                               <input type="text"
                                                                      class="form-control mb-1 input-Sender"
                                                                      v-model="products[index].search"
                                                                      @input="searchProduct(index)"
                                                                      @focus="searchProduct(index)"
                                                                      @blur="isButton = true"
                                                                      autofocus
                                                                      :placeholder="$t('treasury.Search')"
                                                               >
                                                               <ul :class="['dropdown-search',`productSearch-${index}`]" v-if="products[index].products.length">
                                                                   <li
                                                                       v-for="(dropDownSender,ind) in products[index].products"
                                                                       :key="ind"
                                                                       :class="['Sender',`Sender-${index}`]"
                                                                       @mouseenter="productHover($event,index)"
                                                                       @click="showProduct(index,ind)"
                                                                   >
                                                                       {{dropDownSender.name }}
                                                                   </li>
                                                               </ul>

                                                               <input type="text"
                                                                      :class="[
                                                                       'form-control',
                                                                       {'is-invalid':v$.product[index].product_id.$error,'is-valid':!v$.product[index].product_id.$invalid}
                                                                       ]"
                                                                      disabled
                                                                      v-model="products[index].name"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].product_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3">
                                                               <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                               <input type="number" class="form-control"
                                                                      v-model.number="v$.product[index].quantity.$model"
                                                                      @input="DebitAmount"
                                                                      :placeholder="$t('global.RequiredQuantity') + '(' + data.product[index].mainUnitMeasurement +')'"
                                                                      :class="{'is-invalid':v$.product[index].quantity.$error,'is-valid':!v$.product[index].quantity.$invalid}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                   <span v-if="v$.product[index].quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3">
                                                               <label>{{$t('global.price')}} ( {{data.product[index].mainUnitMeasurement}} )</label>
                                                               <input type="number" step="0.1" class="form-control"
                                                                      @input="subPrice(index)"
                                                                      v-model.number="v$.product[index].price.$model"
                                                                      :placeholder="$t('global.price') +' ('+ data.product[index].mainUnitMeasurement +')'"
                                                                      :class="{'is-invalid':v$.product[index].price.$error,'is-valid':!v$.product[index].price.$invalid}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].price.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                   <span v-if="v$.product[index].price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3" v-if="data.product[index].count_unit > 1">
                                                               <label>{{$t('global.countUnitsIN')}} {{data.product[index].mainUnitMeasurement}}</label>
                                                               <input type="number" class="form-control"
                                                                      v-model.number="v$.product[index].count_unit.$model"
                                                                      @input="subPrice(index)"
                                                                      :placeholder="$t('global.countUnits')"
                                                                      :class="{'is-invalid':v$.product[index].count_unit.$error,'is-valid':!v$.product[index].count_unit.$invalid}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].count_unit.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                   <span v-if="v$.product[index].count_unit.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3" v-if="data.product[index].count_unit > 1">
                                                               <label>{{$t('global.RequiredQuantity')}} ( {{data.product[index].subUnitMeasurement}} )</label>
                                                               <input type="number" class="form-control"
                                                                      v-model.number="v$.product[index].sub_quantity.$model"
                                                                      @input="DebitAmount"
                                                                      :placeholder="$t('global.RequiredQuantity') + '(' + data.product[index].subUnitMeasurement +')'"
                                                                      :class="{'is-invalid':v$.product[index].sub_quantity.$error,'is-valid':!v$.product[index].sub_quantity.$invalid}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].sub_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                   <span v-if="v$.product[index].sub_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3" v-if="data.product[index].count_unit > 1">
                                                               <label>{{$t('global.price')}} ( {{data.product[index].subUnitMeasurement}} )</label>
                                                               <input type="number" step="0.1" class="form-control" disabled
                                                                      v-model.number="v$.product[index].sub_price.$model"
                                                                      :placeholder="$t('global.price') +' ('+ data.product[index].subUnitMeasurement +')'"
                                                                      :class="{'is-invalid':v$.product[index].sub_price.$error,'is-valid':!v$.product[index].sub_price.$invalid}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="v$.product[index].sub_price.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                   <span v-if="v$.product[index].sub_price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3">
                                                               <label>{{$t('global.productionDate')}}</label>
                                                               <input type="date" class="form-control"
                                                                      v-model="v$.product[index].production_date.$model"
                                                                      @change="validateProductionDate(index)"
                                                                      :placeholder="$t('global.productionDate')"
                                                                      :class="{'is-invalid':v$.product[index].production_date.$error || !products[index].sendProductionDate,'is-valid':!v$.product[index].production_date.$invalid && products[index].sendProductionDate}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="!products[index].sendProductionDate">{{$t('global.ProductionDateMastLessThanOrEqualToday')}}<br /> </span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3">
                                                               <label>{{$t('global.expiryDate')}}</label>
                                                               <input type="date" class="form-control"
                                                                      @change="validateExpiryDate(index)"
                                                                      v-model="v$.product[index].expiry_date.$model"
                                                                      :placeholder="$t('global.expiryDate')"
                                                                      :class="{'is-invalid':v$.product[index].expiry_date.$error || !products[index].sendExpiryDate,'is-valid':!v$.product[index].expiry_date.$invalid && products[index].sendExpiryDate}"
                                                               >
                                                               <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                               <div class="invalid-feedback">
                                                                   <span v-if="!products[index].sendExpiryDate">{{$t('global.expiryDateMastGreaterThanToday')}}<br /> </span>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 mb-3">
                                                               <button @click.prevent="addDebit" v-if="(data.product.length-1) == index && isButton"
                                                                       class="btn btn-sm btn-success me-2 mt-5">
                                                                   <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                               </button>
                                                               <button v-if="index && isButton" @click.prevent="deleteDebit(index)"
                                                                       data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                   <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                               </button>
                                                           </div>
                                                       </div>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom10">{{ $t('global.TotalPrice') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.price.$model"
                                                       id="validationCustom10"
                                                       :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                       :placeholder="$t('global.TotalPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid">{{ $t('global.TotalPriceIsRequired') }} <br/></span>
                                                    <span v-if="v$.price.numeric.$invalid">{{$t('global.TotalPriceIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Balance')}} : {{parseFloat(Math.round(treasury_amount))}}</span> </label>
                                                <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.ReceivingMethod') }}</label>

                                                <select v-model="data.is_received" :class="['form-select',{'is-invalid':v$.is_received.$error,'is-valid':!v$.is_received.$invalid}]">
                                                    <option value="0">{{$t('global.Make a record of examination and receipt')}}</option>
                                                    <option value="1">{{$t('global.Receipt in stores directly')}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.is_received.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="d-block">{{$t('global.Invoice account with customer account')}}</label>
                                                <a class="btn btn-sm btn-success me-2 d-inline-block" @click="invoiceAndClientAccount">{{$t('global.Calculate')}}</a>
                                                <h5 class="d-inline-block ml-5">{{invoiceClientAccount}}</h5>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.note.$model" :class="['form-control text-height',{'is-invalid':v$.note.$error,'is-valid':!v$.note.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.note.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.note.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit" v-if="isButton">{{$t('global.Submit')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
        <div class="side_amount">
            <div class="side_amount_header">
                <p>{{ $t('global.TotalProductPrice') }}</p>
            </div>
            <div class="side_amount_body">
                <p>{{data.price ? data.price : 0}}</p>
            </div>
        </div>
    </div>
</template>

<script>
import {computed, onMounted, reactive,toRefs,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let products = ref([]);
        let suppliers = ref([]);
        let clients = ref([]);
        let stores = ref([]);
        let treasuries = ref([]);
        let treasury_amount = ref(0);
        let invoiceClientAccount = ref(0);
        let productValidation = ref([]);
        let dropDownSenders = ref([ ]);
        let isButton = ref(true);


        let validateProductionDate = (index) =>{
            addJob.products[index].sendProductionDate = addJob.data.product[index].production_date <= new Date().toISOString().split('T')[0];
        }

        let validateExpiryDate = (index) =>{
            addJob.products[index].sendExpiryDate = addJob.data.product[index].expiry_date > new Date().toISOString().split('T')[0];
        }

        let subPrice = (index)=>{
            addJob.data.product[index].sub_price = parseFloat(addJob.data.product[index].price / addJob.data.product[index].count_unit).toFixed(2);
            DebitAmount();
        }

        let DebitAmount = () => {
            addJob.data.price = 0;
            addJob.data.product.forEach((el) => {
                addJob.data.price += parseFloat(el.price) * parseInt( el.quantity);
                addJob.data.price += parseFloat(el.sub_price) * parseInt( el.sub_quantity);
            });
        }

        let getData = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/purchaseInvoice/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    products.value = l.products;
                    stores.value = l.stores;
                    suppliers.value = l.suppliers;
                    clients.value = l.clients;
                    treasuries.value = l.treasuries;
                    treasury_amount.value = l.treasuries[0].amount;
                    addJob.data.supplier_id  = l.purchase.supplier_id ;
                    addJob.data.store_id = l.purchase.store_id;
                    addJob.data.note = l.purchase.note;
                    addJob.data.price = l.purchase.price;
                    addJob.data.type_invoice = l.purchase.type_invoice;
                    addJob.data.is_received = l.purchase.is_received;
                    addJob.nameSender = parseInt(l.purchase.type_invoice) == 1 ? l.purchase.supplier.name_supplier : l.purchase.user.name;
                    addJob.balance = parseInt(l.purchase.type_invoice) == 1 ? l.purchase.supplier.sum_account : l.purchase.user.sum_account;

                    l.purchase.purchase_products.forEach((el,index)=>{
                        productValidation.value.push({
                            price: {
                                required,
                                numeric
                            },
                            sub_price: {
                                required,
                                numeric
                            },
                            production_date: {},
                            expiry_date: {},
                            quantity: {
                                required,
                                numeric
                            },
                            sub_quantity: {
                                required,
                                numeric
                            },
                            count_unit: {
                                required,
                                numeric
                            },
                            product_id:{
                                required
                            }
                        });
                        addJob.data.product.push({
                            price:el.price,
                            sub_price:parseFloat(el.price / el.count_unit).toFixed(2),
                            production_date:el.production_date,
                            expiry_date:el.expiry_date,
                            quantity:el.quantity,
                            sub_quantity:el.sub_quantity,
                            count_unit:el.count_unit,
                            product_id:el.product_id,
                            subUnitMeasurement:el.product.sub_measurement_unit.name,
                            mainUnitMeasurement:el.product.main_measurement_unit.name,
                        });

                        addJob.products.push({products:[],search:'',name:el.product.name,send:true,sendProductionDate:true,sendExpiryDate:true});
                    });
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onMounted(() => {
            getData();
        });


        let addJob =  reactive({
            data:{
                product:[],
                note:'',
                store_id:1,
                is_received:1,
                type_invoice:1,
                supplier_id:'',
                treasury_id:1,
                price:'',
            },
            products:[],
            nameSender: '',
            search: '',
            balance:0
        });

        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ],
                note:{
                    minLength: minLength(5),
                },
                store_id:{
                    required
                },
                is_received:{
                    required
                },
                type_invoice:{
                    required
                },
                supplier_id:{
                    required
                },
                treasury_id:{
                    required
                },
                price:{
                    required,
                    numeric
                }
            }
        });

        let searchSender = () => {
            dropDownSenders.value = [];
            if(addJob.data.type_invoice == 1){
                if(addJob.search){
                    let thisString = new RegExp(addJob.search,'i');
                    let items = suppliers.value.filter(e => e.name_supplier.match(thisString) || e.id == addJob.search);
                    dropDownSenders.value = items.splice(0,10);
                }else{
                    dropDownSenders.value = [];
                }
            }else{
                if(addJob.search){
                    let thisString = new RegExp(addJob.search);
                    let items = clients.value.filter(e => e.name.match(thisString) || e.id == addJob.search);
                    console.log(items,12354)
                    dropDownSenders.value = items.splice(0,10);
                }else{
                    dropDownSenders.value = [];
                }
            }

            isButton.value = false;
        };

        let showSenderName = (index) => {
            let item = dropDownSenders.value[index];
            addJob.nameSender = addJob.data.type_invoice == 1 ? item.name_supplier : item.name;
            addJob.data.supplier_id = item.id;
            addJob.balance = item.sum_account;
            addJob.search = '';
            dropDownSenders.value = [];
        };

        let senderHover = (e) => {
            let items = document.querySelectorAll('.sender-search .Sender');
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

        document.addEventListener('keyup',(e) => {

            if(e.keyCode == 38){ //top arrow
                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    let isTrue = false;
                    let index = null;
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) {
                            isTrue = true;
                            index = i;
                        }
                    });
                    if(isTrue && index != 0){
                        items[index].classList.remove('active');
                        items[index - 1].classList.add('active');
                    }else if(isTrue && index == 0){
                        items[index].classList.remove('active');
                        items[items.length - 1].classList.add('active');
                    }
                    if(!isTrue) items[0].classList.add('active');
                }else {
                    dropDownSenders.value = [];
                }

                addJob.products.forEach((item,ind) => {
                    if(item.products.length > 0){
                        let products = document.querySelectorAll(`.productSearch-${ind} .Sender`);
                        let isTrue = false;
                        let index = null;
                        products.forEach((e,i) => {
                            if(e.classList.contains('active')) {
                                isTrue = true;
                                index = i;
                            }
                        });
                        if(isTrue && index != 0){
                            products[index].classList.remove('active');
                            products[index - 1].classList.add('active');
                        }else if(isTrue && index == 0){
                            products[index].classList.remove('active');
                            products[products.length - 1].classList.add('active');
                        }
                        if(!isTrue) console.log(products[0].classList.add('active'));
                    }else {
                        item.products = [];
                    }
                });
            };

            if(e.keyCode == 40){ //down arrow
                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    let isTrue = false;
                    let index = null;
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) {
                            isTrue = true;
                            index = i;
                        }
                    });
                    if(isTrue && index != (items.length - 1)){
                        items[index].classList.remove('active');
                        items[index + 1].classList.add('active');
                    }else if(isTrue && index == (items.length - 1)){
                        items[index].classList.remove('active');
                        items[0].classList.add('active');
                    }
                    if(!isTrue) items[items.length - 1].classList.add('active');
                }else {
                    dropDownSenders.value = [];
                }

                addJob.products.forEach((item,ind) => {
                    if(item.products.length > 0){
                        let products = document.querySelectorAll(`.productSearch-${ind} .Sender`);
                        let isTrue = false;
                        let index = null;
                        products.forEach((e,i) => {
                            if(e.classList.contains('active')) {
                                isTrue = true;
                                index = i;
                            }
                        });
                        if(isTrue && index != (products.length - 1)){
                            products[index].classList.remove('active');
                            products[index + 1].classList.add('active');
                        }else if(isTrue && index == (products.length - 1)){
                            products[index].classList.remove('active');
                            products[0].classList.add('active');
                        }
                        if(!isTrue) products[products.length - 1].classList.add('active');
                    }else {
                        item.products = [];
                    }
                });
            };

            if(e.keyCode == 13){ //enter
                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) showSenderName(i);
                    });
                }else {
                    dropDownSenders.value = [];
                }

                addJob.products.forEach((item,ind) => {
                    if(item.products.length > 0){
                        let products = document.querySelectorAll(`.productSearch-${ind} .Sender`);
                        products.forEach((e,i) => {
                            if(e.classList.contains('active')) showProduct(ind,i);
                        });
                    }else {
                        item.products = [];
                    }
                });

                e.target.blur();
            };

        });

        document.addEventListener('click',(e) => {
            if(dropDownSenders.value.length > 0){
                if(!e.target.classList.contains('Sender') && !e.target.classList.contains('input-Sender') && e.pointerType){
                    dropDownSenders.value = [];
                }
            }

            addJob.products.forEach((item,ind) => {
                if(item.products.length > 0){
                    if(
                        !e.target.classList.contains(`Sender-${ind}`) &&
                        !e.target.classList.contains(`input-Sender`) &&
                        e.pointerType
                    ) {
                        item.products = [];
                    }
                }
            });
        });

        let searchProduct = (index) => {
            if(addJob.products[index].search){
                let thisString = new RegExp(addJob.products[index].search,'i');
                let items = products.value.filter(e => e.name.match(thisString) || e.id == addJob.products[index].search);
                addJob.products[index].products = items.splice(0,10);
            }else{
                addJob.products[index].products = [];
            }

            isButton.value = false;
        };

        let showProduct = (index,ind) => {
            let item = addJob.products[index].products[ind];
            addJob.products[index].name = item.name;
            addJob.data.product[index].product_id = item.id;
            addJob.products[index].search = '';
            addJob.products[index].products = [];
            addJob.data.product[index].subUnitMeasurement = item.sub_measurement_unit.name;
            addJob.data.product[index].mainUnitMeasurement = item.main_measurement_unit.name;
            addJob.data.product[index].count_unit = item.count_unit;
        };

        let productHover = (e,index) => {
            let items = document.querySelectorAll(`.productSearch-${index} .Sender`);
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

        let invoiceAndClientAccount = () =>{
            DebitAmount();
            invoiceClientAccount.value = parseFloat(addJob.data.price) +   parseFloat(addJob.balance);
        }

        const v$ = useVuelidate(rules,addJob.data);

        watch(()=>addJob.data.type_invoice,(after,before) =>{
            addJob.nameSender= '';
            addJob.search= '';
            addJob.data.supplier_id= '';
            addJob.balance = 0;
        });

        watch(()=>addJob.data.treasury_id,(after,before) =>{
            let v = treasuries.value.filter((el)=> el.id == addJob.data.treasury_id);
            treasury_amount.value = v[0].amount;
        })

        return {
            t,
            isButton,
            validateExpiryDate,
            validateProductionDate,
            searchSender,
            subPrice,
            showSenderName,
            searchProduct,
            showProduct,
            invoiceAndClientAccount,
            dropDownSenders,
            loading,
            v$,
            DebitAmount,
            products,
            ...toRefs(addJob),
            invoiceClientAccount,
            suppliers,
            stores,
            clients,
            treasuries,
            treasury_amount,
            productValidation,
            senderHover,
            productHover
        };
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/purchaseInvoice/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.getData();
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                        // this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        addDebit(){
            this.data.product.push(
                {
                    price:'',
                    sub_price:0,
                    production_date:'',
                    expiry_date:'',
                    quantity:'',
                    sub_quantity:0,
                    count_unit:'',
                    product_id:'',
                    subUnitMeasurement:'',
                    mainUnitMeasurement:'',
                }
            );
            this.productValidation.push({
                price: {
                    required,
                    numeric
                },
                sub_price: {
                    required,
                    numeric
                },
                production_date: {},
                expiry_date: {},
                quantity: {
                    required,
                    numeric
                },
                sub_quantity: {
                    required,
                    numeric
                },
                count_unit: {
                    required,
                    numeric
                },
                product_id:{
                    required
                }
            });
            this.products.push({products:[],search:'',name:'',send:true,sendProductionDate:true,sendExpiryDate:true});
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index){
            this.data.product.splice(index,1);
            this.productValidation.splice(index,1);
            this.products.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.DebitAmount();
        }
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
.account{
    background-color: #fcb00c;
    color: #000000 !important;
    border-radius: 5px;
}
.head-account{
    display: flex;
    justify-content: center;
}
.head-account h3{
    color: #000000 !important;
    font-weight: bold;
}
.body-account{
    border-top: 3px solid #000000;
    margin: 0 !important;
}
.text-height{
    height: 46px !important;
}
.error-amount{
    display: flex;
    justify-content: center;
    color: red;
    margin: 10px;
}
.dropdown-search{
    position: absolute;
    width: 97%;
    background-color: #fff;
    border: 1px solid #d9d3d3;
    top: 83px;
    z-index: 10;
    padding: 0;
}

.dropdown-search li{
    padding: 5px;
}

.dropdown-search li:not(:last-child){
    border-bottom: 1px solid #d9d3d3;
}

.dropdown-search li:hover{
    background-color: #f1f1f1;
    cursor: pointer;
}
.side_amount{
    position: fixed;
    top: 50%;
    left: 0;
}
.side_amount_header{
    background-color: #28a745;
    padding: 5px;
    color: #fff;
}
.side_amount_body{
    background-color: #e9ecef;
    text-align: center;
    padding: 2px;
}
.balance{
    padding: 0 25px 0 0;
}
.dropdown-search li.active{
    background-color: #f1f1f1;
    cursor: pointer;
}
</style>
