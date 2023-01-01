<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">
            <loader v-if="loading" />
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('sidebar.OrderDirect')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexOrderDirect'}">{{$t('sidebar.OrderDirect')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Add')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexOrderDirect'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                                <a
                                    href="javascript:void(0)"
                                    class="btn mx-2 btn-custom btn-danger"
                                    @click.prevent="resetForm"
                                >
                                    فاتوره جديده
                                </a>
                                <a
                                    href="javascript:void(0)"
                                    class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                    data-bs-target="#edit-category"
                                    v-if="invoicePrint"
                                >
                                    طباعه الفاتوره
                                </a>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['discounts.0']">{{ errors['discounts.0'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['taxs.0']">{{ errors['taxs.0'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.product_id']">{{ errors['product.0.product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.mainId']">{{ errors['product.0.mainId'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.branchId']">{{ errors['product.0.branchId'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.mainQuantity']">{{ errors['product.0.mainQuantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.branchQuantity']">{{ errors['product.0.branchQuantity'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.ChooseStore') }}</label>

                                                <select
                                                    @change="getProductStore"
                                                    v-model="data.store_id"
                                                    :class="['form-select',{'is-invalid':v$.store_id.$error,'is-valid':!v$.store_id.$invalid}]"
                                                >
                                                    <option v-for="store in stores" :key="store.id" :value="store.id">{{store.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.store_id.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3 position-relative">
                                                <label> {{$t('global.ChooseClient')}} </label>
                                                <label class="balance"> {{$t('global.Balance')}} : {{balance}}</label>

                                                <input type="text"
                                                       class="form-control mb-1 input-Sender"
                                                       v-model="search"
                                                       @input="searchSender"
                                                       @blur="isButton = true"
                                                       @focus="searchSender"
                                                       autofocus
                                                       :placeholder="$t('treasury.Search')"
                                                >
                                                <ul class="dropdown-search sender-search" v-if="dropDownSenders.length">
                                                    <li
                                                        class="Sender"
                                                        v-for="(dropDownSender,index) in dropDownSenders"
                                                        :key="index"
                                                        @click="showSenderName(index)"
                                                        @mouseenter="senderHover"
                                                    >
                                                        {{ dropDownSender.id }} - {{ dropDownSender.name }}
                                                    </li>
                                                </ul>

                                                <input type="text"
                                                       :class="['form-control',{'is-invalid':v$.client_id.$error,'is-valid':!v$.client_id.$invalid}]"
                                                       disabled
                                                       v-model="nameSender"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.client_id.required.$invalid">{{$t('global.supplierIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <label>{{ $t('global.sellingMethod') }}</label>
                                                <input
                                                    type="text"
                                                    disabled
                                                    class="form-control mb-1"
                                                    v-model="selling_method_name"
                                                >
                                            </div>

                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Products') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.product" :key="it.id" class="col-md-12 mb-12 body-account row">

                                                        <div class="col-md-2 mb-3 position-relative">
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
                                                            <ul :class="['dropdown-search',`productSearch-${index}`]" v-if="products[index].products.length > 0">
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

                                                        <div class="col-md-2 mb-3" v-if="products[index].productPrice.length > 0">
                                                            <label>{{ products[index].productPrice[0].measurement_unit.name }}
                                                                ( {{ products[index].productPrice[0].available_quantity }} )
                                                            </label>
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                min="0.01"
                                                                :disabled="!permission.includes('avg price product')"
                                                                @input="productPricePartialTotal(index)"
                                                                @blur="avgPricePiece(index)"
                                                                class="form-control mb-1"
                                                                v-model="data.product[index].mainPrice"
                                                            >

                                                            <label>
                                                                {{ $t('global.Quantity') }}
                                                            </label>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                :class="[
                                                                         'form-control mb-1',
                                                                         products[index].isValidProd? '':'is-invalid',
                                                                          {'is-invalid':v$.product[index].branchQuantity.$error,
                                                                          'is-valid':!v$.product[index].branchQuantity.$invalid && products[index].isValidProd}
                                                                       ]"
                                                                @input="productPricePartialTotal(index)"
                                                                v-model="data.product[index].mainQuantity"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div v-if="!products[index].isValidProd || v$.product[index].mainQuantity.$error">
                                                                <span class="text-danger" v-if="v$.product[index].mainQuantity.minValue.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span class="text-danger" v-if="v$.product[index].mainQuantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span class="text-danger"  v-if="!products[index].isValidProd">{{$t('global.isValidProd')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 mb-3" v-if="products[index].productPrice.length > 0 && products[index].product.count_unit > 1">
                                                            <label>
                                                                {{products[index].product.count_unit > 1 ? products[index].productPrice[1].measurement_unit.name : "---"}}
                                                                ({{products[index].product.count_unit > 1 ? products[index].productPrice[1].available_quantity : "---" }})
                                                            </label>
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                :disabled="!permission.includes('avg price product')"
                                                                min="0.01"
                                                                class="form-control mb-1"
                                                                v-model="data.product[index].branchPrice"
                                                                @input="productPricePartialTotal(index)"
                                                            >

                                                            <label>
                                                                {{ $t('global.Quantity') }}
                                                            </label>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                :class="[
                                                                 'form-control mb-1',
                                                                 products[index].isValidProd? '':'is-invalid',
                                                                  {'is-invalid':v$.product[index].branchQuantity.$error,
                                                                  'is-valid':!v$.product[index].branchQuantity.$invalid && products[index].isValidProd}
                                                               ]"
                                                                v-model="data.product[index].branchQuantity"
                                                                @input="productPricePartialTotal(index)"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div v-if="!products[index].isValidProd || v$.product[index].branchQuantity.$error">
                                                                <span class="text-danger" v-if="v$.product[index].branchQuantity.minValue.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span class="text-danger" v-if="v$.product[index].branchQuantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span class="text-danger" v-if="!products[index].isValidProd">{{$t('global.isValidProd')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2 mb-3" v-if="products[index].productPrice.length > 0">
                                                            <label>{{ $t('global.totalProduct') }}</label>
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                disabled
                                                                class="form-control mb-1"
                                                                v-model="products[index].total"
                                                            >
                                                        </div>

                                                        <!-- <div class="col-md-2 mb-2">
                                                            <button @click.prevent="addDebit" v-if="(data.product.length-1) == index && isButton && data.client_id"
                                                                    class="btn btn-sm btn-success mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index && isButton" @click.prevent="deleteDebit(index)"
                                                                    data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger mt-1">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div> -->

                                                        <div class="col-md-2 mb-3" v-if="data.product[index].product_id">
                                                            <p class="middlePrice">
                                                                {{ $t('global.middlePrice') }} : {{products[index].avgPrice}}
                                                            </p>
                                                        </div>

                                                        <div class="col-md-2 mb-3" v-if="data.product[index].product_id">
                                                            <p class="middlePrice">
                                                                {{ $t('global.middlePrice') }} : {{products[index].avgPrice}}
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3" v-if="offerDiscounts.length > 0">
                                                <label>{{ $t('global.offerDisco') }}</label>
                                                <select multiple v-model="data.discounts" :class="['form-select']">
                                                    <option v-for="discount in offerDiscounts" :key="discount.id" :value="discount.id">
                                                        {{discount.name}} -- {{ discount.type == 'fixed' ? `${discount.value} EGP`: `${discount.value} %`}}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3" v-if="taxs.length > 0">
                                                <label>{{ $t('global.tax') }}</label>
                                                <select multiple v-model="data.taxs" :class="['form-select']">
                                                    <option v-for="tax in taxs" :key="tax.id" :value="tax.id">
                                                        {{tax.name}} -- {{tax.percentage}} %
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.otherDiscount') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="data.nameOffer"
                                                       :placeholder="$t('global.otherDiscount')"
                                                >
                                                <div>
                                                    <span class="text-danger" v-if="v$.nameOffer.requiredIf.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.otherPrice') }}</label>
                                                <input type="number" class="form-control"
                                                       step="0.1"
                                                       v-model.trim="data.priceOffer"
                                                       :placeholder="$t('global.otherPrice')"
                                                >
                                                <div>
                                                    <span class="text-danger" v-if="v$.priceOffer.requiredIf.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-5">
                                                <div class="table-responsive">
                                                    <table class="table table-center table-hover mb-0 datatable">
                                                        <thead class="account">
                                                        <tr class="text-center">
                                                            <th>{{ $t('global.TotalPriceBeforeDiscount') }}</th>
                                                            <th>{{ $t('global.TotalPriceAfterDiscount') }}</th>
                                                            <th>{{ $t('global.TotalTax') }}</th>
                                                            <th>{{ $t('global.TotalPriceAfterTaxx') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr class="text-center">
                                                            <td>{{totalProductbefourDiscount ?totalProductbefourDiscount.toFixed(2):0}}</td>
                                                            <td>{{ totalProductAfterDiscount? totalProductAfterDiscount.toFixed(2):0 }}</td>
                                                            <td>{{ totalTax? totalTax.toFixed(2): 0 }} %</td>
                                                            <td>{{ TotalPriceAfterTaxx? (TotalPriceAfterTaxx).toFixed(2):0 }}</td>
                                                        </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit" v-if="isButton && !invoicePrint">{{$t('global.Submit')}}</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div >
            <!-- /Table -->

            <!-- invoice big Modal -->
            <div class="modal fade custom-modal" id="edit-category" v-if="invoicePrint">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" id="print">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                {{ $t('global.InvoiceDetails') }}</h4>
                            <button id="close"  type="button" class="close print-button" data-bs-dismiss="modal">
                                <span>&times;</span></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body row" >

                            <div class="card bg-white projects-card">
                                <div class="card-body pt-0">
                                    <div class="tab-content pt-0">
                                        <div role="tabpanel" :id="'tab-4'" class="tab-pane fade active show">
                                            <loader v-if="loading"/>
                                            <div class="row justify-content-between">
                                                <div class="col-5">
                                                    <button @click="printData()" class="btn btn-secondary print-button head-button">
                                                        {{$t('global.Print')}}
                                                        <i class="fa fa-print"></i>
                                                    </button>
                                                </div>
                                                <div class="col-4 d-flex w-25 justify-content-end"></div>
                                            </div>
                                            <div class="table-responsive" id="printData">
                                                <div class="head-data row">

                                                    <div class="col-md-6 invoice-head">
                                                        <h5>{{$t('global.InvoiceNumber')}} : {{orderInvoice}}</h5>
                                                    </div>

                                                    <div class="col-md-6 image-div">
                                                        <img src="/web/img/logo.png" alt="Logo">
                                                    </div>


                                                    <div class="col-md-6">
                                                        <p>{{$t('global.DateOrder')}} : {{dateFormat()}}</p>
                                                        <p>
                                                            {{$t('global.store')}} :
                                                            {{ data.store_name }}
                                                        </p>
                                                        <p>
                                                            {{$t('global.TotalPriceBeforeDiscount')}} :
                                                            {{ totalProductbefourDiscount }}
                                                            {{ 'EGP' }}
                                                        </p>
                                                        <p>{{$t('global.discountValue')}} : {{ offerDiscount }} {{ 'EGP' }}</p>

                                                        <p v-if="data.priceOffer">
                                                            {{$t('global.otherDiscount')}} :
                                                            {{ data.priceOffer }}
                                                            {{ 'EGP' }}
                                                        </p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p>
                                                            {{$t('global.TotalPriceAfterDiscount')}} :
                                                            {{
                                                                data.priceOffer ?
                                                                    parseFloat( totalProductbefourDiscount - data.priceOffer - offerDiscount) :
                                                                    parseFloat( totalProductbefourDiscount - offerDiscount)
                                                            }}
                                                            {{ 'EGP' }}
                                                        </p>
                                                        <p v-if="taxValue">{{$t('global.taxValue')}} : {{ taxValue }} {{ 'EGP' }}</p>
                                                        <p v-if="taxValue">{{$t('global.TotalPriceAfterTax')}} : {{ parseFloat(TotalPriceAfterTaxx).toFixed(2) }} {{ 'EGP' }}</p>
                                                        <p>{{$t('global.TotalPriceAfterDiscountAndShipping')}} : {{ parseFloat(TotalPriceAfterTaxx).toFixed(2) }} {{ 'EGP' }}</p>
                                                    </div>

                                                </div>

                                                <table class="table table-center table-hover mb-0 datatable">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ $t('global.Products') }}</th>
                                                        <th>{{ $t('global.full') }}</th>
                                                        <th>{{ $t('global.partial') }}</th>
                                                        <th>{{ $t('global.fullPrice') }}</th>
                                                        <th>{{ $t('global.partialPrice') }}</th>
                                                        <th>{{ $t('global.TotalPrice') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody v-if="data.product.length">
                                                    <tr v-for="(it,index) in data.product" :key="index">
                                                        <td>{{ index +1}}</td>
                                                        <td>{{ products[index].product.name }}</td>
                                                        <td>{{ data.product[index].mainQuantity }} ( {{products[index].productPrice[0].measurement_unit.name}} )</td>
                                                        <td>{{ products[index].product.count_unit > 1 ? data.product[index].branchQuantity : "-" }} ( {{ products[index].product.count_unit > 1 ? products[index].productPrice[1].measurement_unit.name : "---"}} )</td>
                                                        <td>{{data.product[index].mainQuantity? data.product[index].mainPrice : '-'}}</td>
                                                        <td>{{data.product[index].branchQuantity? data.product[index].branchPrice : '-'}}</td>
                                                        <td>
                                                            {{
                                                                parseFloat(
                                                                    (data.product[index].mainQuantity * data.product[index].mainPrice) +
                                                                    (data.product[index].branchQuantity * data.product[index].branchPrice)
                                                                ).toFixed(2)
                                                            }}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                    <tbody  v-else>
                                                    <tr>
                                                        <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /invoice big Modal-->

        </div>
    </div>
</template>

<script>
import {computed, onMounted, reactive,toRefs,inject,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, helpers, requiredIf, numeric,minValue} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import {useStore} from "vuex";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "create",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        const {t} = useI18n({});
        let loading = ref(false);
        let isButton = ref(true);
        let dropDownSenders = ref([ ]);
        let stores = ref([]);
        let clients = ref([]);
        let taxs = ref([]);
        let taxValue = ref(0);
        let offerDiscount = ref(0);
        let products = ref([]);
        let offerDiscounts = ref([]);
        let productValidation = ref([{
            mainPrice: {required, numeric},
            branchPrice: {required, numeric},
            mainQuantity: { numeric, minValue: minValue(0),required},
            branchQuantity: {required,numeric, minValue: minValue(0)},
            product_id:{required}
        }]);
        let invoicePrint = ref(false);
        let totalProductbefourDiscount = ref(0);
        let orderInvoice = 0;

        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);

        let TotalPriceAfterTaxx = ref(0);
        let totalProductAfterDiscount = ref(0);
        let totalTax = ref(0);

        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/orderDirect/create`)
                .then((res) => {
                    let l = res.data.data;
                    taxs.value = l.taxs;
                    offerDiscounts.value = l.offerDiscounts;
                    clients.value = l.clients;
                    stores.value = l.stores;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        let getProductStore = () => {
            loading.value = true;
            adminApi
                .get(`/v1/dashboard/orderDirectStore?store_id=${addJob.data.store_id}&selling_method_id=${addJob.data.selling_method_id}`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l)
                    products.value = l.products;
                    addJob.data.product= [{
                        mainPrice: 0,
                        branchPrice: 0,
                        mainQuantity: 0,
                        branchQuantity: 0,
                        product_id:null,
                        mainId: null,
                        branchId: null
                    }];
                    productValidation.value = [{
                        mainPrice: {required, numeric},
                        branchPrice: {required, numeric},
                        mainQuantity: { numeric, minValue: minValue(0),required},
                        branchQuantity: {required,numeric, minValue: minValue(0)},
                        product_id:{required}
                    }];
                    addJob.products= [{products:[],total:0,product:{},avgPrice:0,productPrice:[],isValidProd:true,search:'',name:''}];
                })
                .catch((err) => {
                    console.log(err.response);
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
                product:[
                    {
                        mainPrice: 0,
                        branchPrice: 0,
                        mainQuantity: 0,
                        branchQuantity: 0,
                        product_id:null,
                        mainId: null,
                        branchId: '',
                    }
                ],
                discounts:[],
                taxs: [],
                nameOffer:'',
                priceOffer: 0,
                store_id: 1,
                store_name: '',
                shippingPrice:0,
                selling_method_id:null,
                client_id: null,
            },
            products:[
                {products:[],product:{},productPrice:[],total:0,avgPrice:0,isValidProd:true,search:'',name:''}
            ],
            nameSender: '',
            search: '',
            selling_method_name: '',
            balance: 0
        });
        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ],
                client_id:{required},
                store_id:{required},
                nameOffer: {
                    requiredIf: requiredIf(addJob.data.priceOffer)
                },
                priceOffer: {
                    requiredIf: requiredIf(addJob.data.nameOffer)
                }
            }
        });
        const v$ = useVuelidate(rules,addJob.data);

        let searchSender = () => {
            dropDownSenders.value = [];
            if(addJob.search){
                let thisString = new RegExp(addJob.search,'i');
                let items = clients.value.filter(e => e.name.match(thisString) || e.id == addJob.search);
                dropDownSenders.value = items.splice(0,10);
            }else{
                dropDownSenders.value = [];
            }
            isButton.value = false;
        };
        let showSenderName = (index) => {
            let item = dropDownSenders.value[index];
            addJob.nameSender = `${item.id} - ${item.name}`;
            addJob.data.client_id = item.id;
            addJob.balance = item.sum_account;
            addJob.data.selling_method_id = item.client.selling_method_id;
            addJob.selling_method_name = item.client.selling_method.name;
            addJob.search = '';
            dropDownSenders.value = [];
            getProductStore();
        };
        let senderHover = (e) => {
            let items = document.querySelectorAll('.sender-search .Sender');
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

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
            addJob.products[index].product = item;
            addJob.data.store_name = stores.value.find(e => e.id == addJob.data.store_id).name;
            addJob.products[index].productPrice = item.product_price;
            addJob.data.product[index].mainPrice = item.product_price[0].price;
            addJob.data.product[index].branchPrice = item.count_unit > 1 ? item.product_price[1].price : 0;
            addJob.data.product[index].mainId = item.product_price[0].id;
            addJob.data.product[index].branchId = item.count_unit > 1 ? item.product_price[1].id : "";
            addJob.products[index].name = item.name;
            addJob.data.product[index].product_id = item.id;
            if(item.store_products.length >= 1){
                let quantity = 0;
                let price  = 0;
                item.store_products.forEach((e) => {
                    quantity += e.quantity;
                    price += e.purchase_product.price * e.quantity;
                },0);
                addJob.products[index].avgPrice = (price/quantity).toFixed(2);
            }
            addJob.products[index].search = '';
            addJob.products[index].products = [];
        };
        let productHover = (e,index) => {
            let items = document.querySelectorAll(`.productSearch-${index} .Sender`);
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

        let productPricePartialTotal = (index) => {
            let mainPrice = addJob.data.product[index].mainPrice * addJob.data.product[index].mainQuantity;
            let branchPrice = addJob.data.product[index].branchPrice * addJob.data.product[index].branchQuantity;
            addJob.products[index].total = (mainPrice + branchPrice).toFixed(2);

            addJob.products[index].isValidProd =
                ((addJob.data.product[index].mainQuantity * addJob.products[index].product.count_unit) +
                    addJob.data.product[index].branchQuantity) <=  addJob.products[index].productPrice[1].available_quantity;

            totalProductFun();
        };

        const totalProductFun = () => {
            totalProductbefourDiscount.value = 0;
            TotalPriceAfterTaxx.value = 0;
            totalProductAfterDiscount.value = 0;
            offerDiscount.value = 0;
            taxValue.value = 0;

            let total = 0;
            addJob.products.forEach((el) => {
                total += parseFloat(el.total);
            });
            totalProductbefourDiscount.value = total;

            let offer = 0;
            totalTax.value = 0;
            offerDiscounts.value.forEach((el) => {
                if(addJob.data.discounts.includes(el.id)){
                    if(el.type  == 'fixed'){
                        offer += parseFloat(el.value);
                    }else{
                        offer += parseFloat(el.value * totalProductbefourDiscount.value / 100);
                    }
                }
            });
            offerDiscount.value = offer;
            totalProductAfterDiscount.value = totalProductbefourDiscount.value - offer - addJob.data.priceOffer;

            taxs.value.forEach((el) => {
                if(addJob.data.taxs.includes(el.id)){
                    totalTax.value += parseFloat(el.percentage);
                }
            });
            taxValue.value = ((totalProductAfterDiscount.value * totalTax.value) / 100);
            TotalPriceAfterTaxx.value = parseFloat((totalProductAfterDiscount.value) + ((totalProductAfterDiscount.value * totalTax.value) / 100));
        };

        const avgPricePiece = (index) => {
            if(addJob.data.product[index].mainPrice > addJob.products[index].avgPrice){
                Swal.fire(
                    `${addJob.products[index].product.name}`,
                    'السعر اكبر من المتوسط'
                )
            }else if(addJob.data.product[index].mainPrice < addJob.products[index].avgPrice){
                Swal.fire(
                    `${addJob.products[index].product.name}`,
                    'السعر اقل من المتوسط'
                )
            }else{
                Swal.fire(
                    `${addJob.products[index].product.name}`,
                    'السعر يساوي  المتوسط',
                )
            }
        };

        let close=()=>{
            document.getElementById('close').click();
        }

        let dateFormat = () => {
            let now = new Date(Date.now());
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        }

        watch(() => addJob.data.discounts,(after, before) => {
            totalProductFun();
        });

        watch(totalProductbefourDiscount,(after, before) => {
            totalProductFun();
        });

        watch(() => addJob.data.taxs,(after, before) => {
            totalProductFun();
        });

        watch(() =>  addJob.data.priceOffer,(after, before) => {
            totalProductFun();
        });

        watch(() =>  addJob.data.shippingPrice,(after, before) => {
            totalProductFun();
        });

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

        let printData = () => {
            var printContents = document.getElementById('printData').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {
            t,
            isButton,
            dropDownSenders,
            clients,
            searchSender,
            showSenderName,
            senderHover,
            searchProduct,
            stores,
            productPricePartialTotal,
            loading,
            taxs,
            offerDiscounts,
            getProductStore,
            products,
            ...toRefs(addJob),
            getData,
            showProduct,
            productHover,
            productValidation,
            invoicePrint,
            totalProductbefourDiscount,
            totalProductAfterDiscount,
            totalTax,
            avgPricePiece,
            TotalPriceAfterTaxx,
            orderInvoice,
            dateFormat,
            taxValue,
            close,
            totalProductFun,
            offerDiscount,
            v$,
            printData,
            permission
        };
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            let IsValidPro = this.products.every(e => e.isValidProd);

            if(!this.v$.$error && IsValidPro){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/orderDirect`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.orderInvoice = res.data.data.order.id;
                        this.invoicePrint = true;

                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        addDebit(){
            this.data.product.push({
                mainPrice: 0,
                branchPrice: 0,
                mainQuantity: 0,
                branchQuantity: 0,
                product_id:null,
                mainId: null,
                branchId: null,
            });
            this.productValidation.push({
                mainPrice: {required, numeric},
                branchPrice: {required, numeric},
                mainQuantity: { numeric, minValue: minValue(0),required},
                branchQuantity: {required,numeric, minValue: minValue(0)},
                product_id:{required}
            });
            this.products.push({products:[],product:{},productPrice:[],total:0,avgPrice:0,isValidProd:true,search:'',name:''});
            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index){
            this.data.product.splice(index,1);
            this.productValidation.splice(index,1);
            this.products.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.totalProductFun();
        },
        resetForm(){
            this.data.discounts = [];
            this.data.taxs = [];
            this.nameSender = '';
            this.data.client_id = null;
            this.data.nameOffer = '';
            this.data.priceOffer = 0;
            this.totalProductbefourDiscount = 0;
            this.totalProductAfterDiscount = 0;
            this.TotalPriceAfterTaxx = 0;
            this.totalTax = 0;
            this.ispPartialTotal = false;
            this.invoicePrint = false;
            this.data.product =[{
                mainPrice: 0,
                branchPrice: 0,
                mainQuantity: 0,
                branchQuantity: 0,
                product_id:null,
                mainId: null,
                branchId: null,
            }];
            this.productValidation = [{
                mainPrice: {required, numeric},
                branchPrice: {required, numeric},
                mainQuantity: { numeric, minValue: minValue(0),required},
                branchQuantity: {required,numeric, minValue: minValue(0)},
                product_id:{required}
            }];
            this.products = [{products:[],product:{},productPrice:[],total:0,avgPrice:0,isValidProd:true,search:'',name:''}];
            this.$nextTick(() => { this.v$.$reset() });
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
    background-color: #103C67;
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

.modal-dialog {
    max-width: 750px !important;
}

.custom-modal .close {
    line-height: 25px;
}

.balance{
    padding: 0 25px 0 0;
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

.dropdown-search li.active{
    background-color: #f1f1f1;
    cursor: pointer;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    margin: auto;
    width: 300px;
    max-width: 300px;
}

img {
    max-width: inherit;
    width: inherit;
}
</style>
