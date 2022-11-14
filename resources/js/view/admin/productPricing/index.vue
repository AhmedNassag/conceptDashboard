<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.ProductsPricing') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.ProductsPricing') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading" />
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['product_id']">{{ errors['product_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.id']">{{ errors['product.0.id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.less_quantity']">{{ errors['product.0.less_quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.max_quantity']">{{ errors['product.0.max_quantity'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.active']">{{ errors['product.0.active'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.selling_method_id']">{{ errors['product.0.selling_method_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.profit']">{{ errors['product.0.profit'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.profit_percentage']">{{ errors['product.0.profit_percentage'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product.0.price']">{{ errors['product.0.price'][0] }}<br /> </div>

                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3 position-relative">
                                                <label>{{ $t('global.Products') }}</label>
                                                <input type="text"
                                                       class="form-control mb-1"
                                                       v-model="search"
                                                       @input="searchProduct"
                                                       @focus="searchProduct"
                                                       autofocus
                                                       :placeholder="$t('treasury.Search')"
                                                >
                                                <ul class="dropdown-search" v-if="dropDownSenders.length">
                                                    <li
                                                        v-for="(dropDownSender,index) in dropDownSenders"
                                                        :key="index"
                                                        @click="showProduct(index)"
                                                    >
                                                        {{dropDownSender.name }}
                                                    </li>
                                                </ul>

                                                <input type="text"
                                                       :class="['form-control',{'is-invalid':v$.product_id.$error,'is-valid':!v$.product_id.$invalid}]"
                                                       disabled
                                                       v-model="nameSender"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.product_id.required.$invalid">{{$t('global.supplierIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-2 mb-3" v-if="data.product_id">
                                                <label>{{$t('global.LastPurchasingPrice')}} ( {{mainType}} ) </label>
                                                <input type="number" disabled class="form-control"
                                                       v-model="purchasing_price_main">
                                            </div>

                                            <div class="col-md-2 mb-3" v-if="data.product_id">
                                                <label>{{$t('global.LastPurchasingPrice')}} ( {{subType}} ) </label>
                                                <input type="number" disabled class="form-control"
                                                       v-model="purchasing_price_sub">
                                            </div>

                                            <div class="col-md-3 mb-3" v-if="data.product_id">
                                                <label>{{$t('global.ProductDetailsInStock')}}</label>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                   :data-bs-target="'#edit-category-1'">
                                                    <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                                </a>
                                            </div>

                                            <!-- Edit Modal -->
                                            <div class="modal fade custom-modal" :id="'edit-category-1'">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="print">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ $t('global.ProductDetailsInStock') }}</h4>
                                                            <button :id="'close'"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                                <span>&times;</span></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body row" >

                                                            <div class="card bg-white projects-card">
                                                                <div class="card-body pt-0">
                                                                    <div class="tab-content pt-0">
                                                                        <div role="tabpanel" class="tab-pane fade active show">
                                                                            <loader v-if="loading"/>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ $t('global.Store') }}</th>
                                                                                        <th>{{ $t('global.productStatus') }}</th>
                                                                                        <th>{{ $t('global.Quantity') }} ({{mainType}})</th>
                                                                                        <th>{{ $t('global.count') }} ({{subType}}) {{ $t('global.in') }} ({{mainType}})</th>
                                                                                        <th>{{ $t('global.Quantity') }} ({{subType}})</th>
                                                                                        <th>{{ $t('global.productionDate') }}</th>
                                                                                        <th>{{ $t('global.expiryDate') }}</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody v-if="storeProducts.length">
                                                                                        <tr v-for="(it,index) in storeProducts" :key="it.id">
                                                                                            <td>{{ index +1}}</td>
                                                                                            <td>{{ it.store.name }}</td>
                                                                                            <td>{{ it.product_status.name }}</td>
                                                                                            <td>{{ it.main_quantity }}</td>
                                                                                            <td>{{ it.count_unit }}</td>
                                                                                            <td>{{ it.sub_quantity_order }}</td>
                                                                                            <td>{{ it.production_date ?? '---' }}</td>
                                                                                            <td>{{ it.expiry_date ?? '---' }}</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    <tbody v-else>
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
                                            <!-- /Edit Modal -->

                                            <div class="col-md-12 mb-12" v-if="data.product_id">
                                                <div class="row account">
                                                    <div v-for="(it,index) in data.product" class="col-md-12 mb-12 body-account row">
                                                        <div class="col-md-12">
                                                            <hr class="hr-head" v-if="(index > 0 && data.product[index].selling_method_id !== data.product[index -1].selling_method_id)">
                                                        </div>
                                                        <div class="col-md-12 mb-12 head-account" v-if="(index > 0 && data.product[index].selling_method_id !== data.product[index -1].selling_method_id) || (index == 0)">
                                                            <h3> {{$t('global.Selling method')}} {{data.product[index].selling_method_name}}</h3>
                                                        </div>
                                                        <div class="col-md-3 mb-3 mt-5">
                                                          <h4>{{data.product[index].measurement_unit_name}}</h4>
                                                        </div>
                                                        <div class="col-md-3 mb-3 mt-3" >
                                                            <label>{{$t('global.profit')}} </label>
                                                            <input type="number" disabled class="form-control"
                                                                   :class="{'is-invalid':data.product[index].profit <= 0,'is-valid':data.product[index].profit > 0}"
                                                                   v-model="data.product[index].profit">
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="data.product[index].profit <= 0">{{$t('global.ThereIsALoss')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3 mt-3" >
                                                            <label>{{$t('global.profitPercentage')}} </label>
                                                            <input type="number" disabled class="form-control"
                                                                   :class="{'is-invalid':data.product[index].profit_percentage <= 0,'is-valid':data.product[index].profit_percentage > 0}"
                                                                   v-model="data.product[index].profit_percentage">
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="data.product[index].profit_percentage <= 0">{{$t('global.ThereIsALoss')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3 mt-3">
                                                            <label>{{ $t('global.Status') }}</label>

                                                            <select v-model="v$.product[index].active.$model" class="form-control" :class="{'is-invalid':v$.product[index].active.$error,'is-valid':!v$.product[index].active.$invalid}">
                                                                <option value="1">{{ $t('treasury.Active') }}</option>
                                                                <option value="0">{{ $t('treasury.Inactive') }}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].active.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.SellingPrice')}}</label>
                                                            <input @input="profitAccount(index)" type="number" class="form-control"
                                                                   step="0.01"
                                                                   v-model.number="v$.product[index].price.$model"
                                                                   :placeholder="$t('global.SellingPrice')"
                                                                   :class="{'is-invalid':v$.product[index].price.$error,'is-valid':!v$.product[index].price.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].price.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.LessimumOrderQuantity')}}</label>
                                                            <input type="number" class="form-control"
                                                                   v-model.number="v$.product[index].less_quantity.$model"
                                                                   :placeholder="$t('global.LessimumOrderQuantity')"
                                                                   :class="{'is-invalid':v$.product[index].less_quantity.$error || data.product[index].less_quantity > data.product[index].max_quantity,'is-valid':!v$.product[index].less_quantity.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].less_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].less_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                <span v-if="data.product[index].less_quantity > data.product[index].max_quantity">{{$t('global.ThisFieldMustBeLessThan')}} ({{ data.product[index].max_quantity }}) <br /></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mb-3">
                                                            <label>{{$t('global.MaximumOrderQuantity')}}</label>
                                                            <input type="number" class="form-control"
                                                                   v-model.number="v$.product[index].max_quantity.$model"
                                                                   :placeholder="$t('global.MaximumOrderQuantity')"
                                                                   :class="{'is-invalid':v$.product[index].max_quantity.$error,'is-valid':!v$.product[index].max_quantity.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.product[index].max_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                <span v-if="v$.product[index].max_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <button class="btn btn-primary mt-3" type="submit">{{$t('global.Submit')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref, computed, reactive, toRefs} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";
import {notify} from "@kyvg/vue3-notification";
import {maxLength, minLength, numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
    name: "update product pricing",
    data(){
        return {
            errors:{}
        }
    },
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let products = ref([]);
        let productValidation = ref([]);
        let unitTypes = ref([]);
        let storeProducts = ref([]);
        let dropDownSenders = ref([]);
        let mainType = ref('');
        let subType = ref('');
        let purchasing_price_main = ref(0);
        let main_measurement_unit_id = ref(0);
        let purchasing_price_sub = ref(0);
        let count_unit = ref(0);
        let loading = ref(false);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/productsPricing`)
                .then((res) => {
                    let l = res.data.data;
                    products.value = l.products;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onMounted(() => {
            getProducts();
        });


        let profitAccount = (index) =>{
            if (addJob.data.product[index].measurement_unit_id == main_measurement_unit_id.value )
            {
                addJob.data.product[index].profit = addJob.data.product[index].price - purchasing_price_main.value;
                addJob.data.product[index].profit = parseFloat(addJob.data.product[index].profit).toFixed(2);
                addJob.data.product[index].profit_percentage = ((addJob.data.product[index].price / purchasing_price_main.value)*100) - 100;
                addJob.data.product[index].profit_percentage = parseFloat(addJob.data.product[index].profit_percentage).toFixed(2);

                addJob.data.product[index+1].price = addJob.data.product[index].price / count_unit.value;
                addJob.data.product[index+1].price = parseFloat(addJob.data.product[index].price / count_unit.value).toFixed(2);
                addJob.data.product[index+1].profit = addJob.data.product[index+1].price - purchasing_price_sub.value;
                addJob.data.product[index+1].profit = parseFloat(addJob.data.product[index+1].profit).toFixed(2);
                addJob.data.product[index+1].profit_percentage = ((addJob.data.product[index+1].price / purchasing_price_sub.value)*100) - 100;
                addJob.data.product[index+1].profit_percentage = parseFloat(addJob.data.product[index+1].profit_percentage).toFixed(2);
            }else {
                addJob.data.product[index].profit = addJob.data.product[index].price - purchasing_price_sub.value;
                addJob.data.product[index].profit = parseFloat( addJob.data.product[index].profit).toFixed(2);
                addJob.data.product[index].profit_percentage = ((addJob.data.product[index].price / purchasing_price_sub.value)*100) - 100;
                addJob.data.product[index].profit_percentage = parseFloat(addJob.data.product[index].profit_percentage).toFixed(2);
            }
        }

        let getProductPricing = (id) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/productsPricing/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.data.product = [];
                    unitTypes.value = [];
                    storeProducts.value = [];
                    purchasing_price_main.value = 0 ;
                    purchasing_price_sub.value = 0 ;
                    main_measurement_unit_id.value = 0;
                    main_measurement_unit_id.value = l.product.main_measurement_unit_id;
                    storeProducts.value =  l.product.store_products.filter(el => el.sub_quantity_order > 0);
                    count_unit.value =  l.product.count_unit;
                    mainType.value = l.product.main_measurement_unit.name;
                    subType.value = l.product.sub_measurement_unit.name;
                    unitTypes.value.push({
                        id:l.product.main_measurement_unit_id,
                        name:l.product.main_measurement_unit.name,
                    })
                    unitTypes.value.push({
                        id:l.product.sub_measurement_unit_id,
                        name:l.product.sub_measurement_unit.name,
                    })
                    if (l.product.purchase_products.length > 0)
                    {
                        purchasing_price_main.value = l.product.purchase_products[l.product.purchase_products.length - 1].price ;
                        purchasing_price_sub.value = purchasing_price_main.value / l.product.purchase_products[l.product.purchase_products.length - 1].count_unit;
                        purchasing_price_sub.value = parseFloat(purchasing_price_sub.value).toFixed(2);
                    }

                    l.product.product_price.forEach((el,index)=> {
                        let profit = 0;
                        let profit_percentage = 0;
                        if(el.measurement_unit_id == l.product.main_measurement_unit_id){
                            profit = el.price - purchasing_price_main.value;
                            profit_percentage = ((el.price / purchasing_price_main.value) * 100) -100;
                        }else {
                            profit = el.price - purchasing_price_sub.value;
                            profit_percentage = ((el.price / purchasing_price_sub.value) * 100) -100 ;
                        }
                        addJob.data.product.push({
                            price: el.price,
                            id: el.id,
                            max_quantity: el.max_quantity,
                            less_quantity: el.less_quantity,
                            active: el.active,
                            selling_method_id : el.selling_method_id,
                            selling_method_name : el.selling_method.name,
                            measurement_unit_id : el.measurement_unit_id,
                            measurement_unit_name : el.measurement_unit.name,
                            profit :parseFloat(profit).toFixed(2),
                            profit_percentage : parseFloat(profit_percentage).toFixed(2),
                        });
                        productValidation.value.push({
                            price: {
                                required,
                                numeric
                            },
                            less_quantity: {
                                required,
                                numeric
                            },
                            max_quantity: {
                                required,
                                numeric
                            },
                            measurement_unit_id: {required},
                            active: {required}
                        });
                    })
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let addJob =  reactive({
            data:{
                product:[],
                product_id : '',
                products:[],

            },
            nameSender: '',
            search: '',
        });

        const rules = computed(() => {
            return {
                product:[
                    ...productValidation.value
                ],
                product_id: {
                    required
                }
            }
        });
        let searchProduct = (index) => {
            dropDownSenders.value = [];
            if(addJob.search){
                let thisString = new RegExp(addJob.search,'i');
                let items = products.value.filter(e => e.name.match(thisString) || e.id == addJob.search);
                dropDownSenders.value = items.splice(0,10);
            }else{
                dropDownSenders.value = [];
            }
        };

        let showProduct = (index) => {
            let item = dropDownSenders.value[index];
            addJob.nameSender =  item.name;
            addJob.data.product_id = item.id;
            addJob.search = '';
            dropDownSenders.value = [];
            getProductPricing(addJob.data.product_id);
        };

        const v$ = useVuelidate(rules,addJob.data);

        return {t,searchProduct,showProduct,dropDownSenders,...toRefs(addJob),v$,profitAccount,getProductPricing,mainType,subType,storeProducts,unitTypes,purchasing_price_main,purchasing_price_sub,productValidation,permission,products, loading,getProducts};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/productsPricing`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.getProducts();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.product_id = '';
            this.data.product = [];
            this.subCategories = [];
            this.products = [];
            this.productValidation = [];
            this.unitTypes = [];
            this.storeProducts = [];
            this.mainType = '';
            this.subType = '';
            this.category_id = '';
            this.subCategory_id = '';
            this.purchasing_price_main = 0;
            this.main_measurement_unit_id = 0;
            this.purchasing_price_sub = 0;
            this.nameSender= '';
            this.search= '';
        }
    }
}
</script>

<style scoped>

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
    padding: 3px;
    border-bottom: 3px solid #000000;
    color: #000000 !important;
    font-weight: bold;
}
.body-account{
    border-bottom: 3px dashed #000000;
    margin: 0 !important;
}
.card {
    position: relative;
}
.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}

.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
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
.hr-head{
    background-color: #000;
    height: 20px;
    opacity: 1;
    margin-top: -11px;
}
</style>