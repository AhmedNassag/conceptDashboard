<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.PurchaseReturn') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.PurchaseReturn') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">

                                                <div class="col-md-3">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="fromDate">
                                                </div>

                                                <div class="col-md-3">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="toDate">
                                                </div>

                                                <div class="col-md-4">

                                                    <label>{{$t('global.ChooseSupplier')}} </label>

                                                    <select v-model="supplier_id" class="form-select select-input">
                                                        <option value="">----</option>
                                                        <option v-for="supplier in suppliers" :kay="supplier.id" :value="supplier.id">{{supplier.name_supplier}}</option>
                                                    </select>

                                                </div>

                                                <div class="col-md-2 mt-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>

                                    <div class="col-md-2 row justify-content-end">
                                        <router-link v-if="permission.includes('PurchaseReturn create')"
                                                     :to="{name: 'createPurchaseReturn', params: {lang: locale || 'ar'}}"
                                                     class="btn btn-custom btn-warning">
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{$t('global.Storekeeper')}}</th>
                                            <th>{{$t('global.PurchaseInvoiceNumber')}}</th>
                                            <th>{{ $t('global.Supplier') }}</th>
                                            <th>{{ $t('global.Store') }}</th>
                                            <th>{{$t('global.TotalPrice')}}</th>
                                            <th>{{ $t('global.Notes') }}</th>
                                            <th>{{ $t('global.Date') }}</th>
                                            <th>{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="purchases.length">
                                        <tr v-for="(item,index) in purchases"  :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.user.name }}</td>
                                            <td>{{ item.purchase_id ?? '--'}}</td>
                                            <td>{{ item.sender_name }}</td>
                                            <td>{{ item.store.name }}</td>
                                            <td>{{ item.total_price }} ج.م</td>
                                            <td>{{ item.note ?? '---' }}</td>
                                            <td>{{ item.created_at ? dateFormat(item.created_at) : '--' }}</td>

                                            <td>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                   :data-bs-target="'#edit-category'+item.id">
                                                    <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                                </a>
                                                <a href="#" @click="deletePurchaseReturn(item.id,index)"
                                                   v-if="permission.includes('PurchaseReturn delete') && !item.purchase && parseInt(item.is_account) == 0"
                                                   data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>

                                                <a href="javascript:void(0);"
                                                   v-if="parseInt(item.is_account) == 0"
                                                   @click="dataCash(item.id)"
                                                   class="btn btn-sm btn-success me-2" data-bs-toggle="modal"
                                                   :data-bs-target="'#ReceiveCash-'+item.id">
                                                    <i class="fas fa-money-bill-wave"></i> {{$t('global.receiveCash')}}
                                                </a>

                                                <a href="javascript:void(0);"
                                                   v-if="parseInt(item.is_account) == 1"
                                                   class="btn btn-sm btn-secondary me-2" >
                                                    <i class="fas fa-money-bill-wave"></i> {{$t('global.cashReceived')}}
                                                </a>
                                            </td>

                                            <!-- Edit Modal -->
                                            <div class="modal fade custom-modal" :id="'ReceiveCash-'+item.id">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ $t('global.receiveCash') }}</h4>
                                                            <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                                <span>&times;</span></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body row" >

                                                            <div class="card bg-white projects-card">
                                                                <div class="card-body pt-0">
                                                                    <div class="tab-content pt-0">
                                                                        <div role="tabpanel" class="tab-pane fade active show">
                                                                            <loader v-if="loading"/>
                                                                            <div class="alert alert-danger text-center" v-if="errors['type_invoice']">{{ errors['type_invoice'][0] }}<br /> </div>
                                                                            <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                                                            <div class="alert alert-danger text-center" v-if="errors['purchase_return_id']">{{ errors['purchase_return_id'][0] }}<br /> </div>
                                                                            <div class="alert alert-danger text-center" v-if="errors['treasury_amount']">{{ errors['treasury_amount'][0] }}<br /> </div>
                                                                            <div class="alert alert-danger text-center" v-if="errors['sender_amount']">{{ errors['sender_amount'][0] }}<br /> </div>
                                                                            <div class="table-responsive">
                                                                                <form @submit.prevent="storeJob" class="needs-validation">
                                                                                    <div class="form-row row">
                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label>{{ $t('global.CashReceiptMethod') }}</label>

                                                                                            <select v-model="data.type_invoice" :class="['form-select',{'is-invalid':v$.type_invoice.$error,'is-valid':!v$.type_invoice.$invalid}]">
                                                                                                <option value="0">{{$t('global.AddToSupplierAccount')}}</option>
                                                                                                <option value="1">{{$t('global.receiptInTheSafe')}}</option>
                                                                                                <option value="2">{{$t('global.ReceiptInTheSafeAndTheSuppliersAccount')}}</option>
                                                                                            </select>
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.type_invoice.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 1 || data.type_invoice == 2">
                                                                                            <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Balance')}} : {{parseFloat(Math.round(treasury_amount))}}</span> </label>
                                                                                            <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                                                                <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                                                            </select>

                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 1 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.AddedToTheTreasury')}}</label>
                                                                                            <input type="number" class="form-control"
                                                                                                   v-model.number="v$.treasury_amount.$model"
                                                                                                   :disabled="data.type_invoice == 1"
                                                                                                   :max="item.total_price"
                                                                                                   :class="{'is-invalid':v$.treasury_amount.$error,'is-valid':!v$.treasury_amount.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.treasury_amount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.treasury_amount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 0 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.Supplier')}}</label>
                                                                                            <input type="text" class="form-control"
                                                                                                   v-model="data.name_supplier" disabled>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3" v-if="data.type_invoice == 0 || data.type_invoice == 2">
                                                                                            <label>{{$t('global.AddedToTheSupplier')}}</label>
                                                                                            <input type="number" class="form-control"
                                                                                                   v-model.number="v$.sender_amount.$model"
                                                                                                   :disabled="data.type_invoice == 0"
                                                                                                   :max="item.total_price"
                                                                                                   :class="{'is-invalid':v$.sender_amount.$error,'is-valid':!v$.sender_amount.$invalid}"
                                                                                            >
                                                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                            <div class="invalid-feedback">
                                                                                                <span v-if="v$.sender_amount.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                                <span v-if="v$.sender_amount.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>

                                                                                    <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                                                                </form>
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

                                            <!-- Edit Modal -->
                                            <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="print">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ $t('global.Show') }}</h4>
                                                            <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                                <span>&times;</span></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body row" >

                                                            <div class="card bg-white projects-card">
                                                                <div class="card-body pt-0">
                                                                    <div class="reviews-menu-links">
                                                                        <ul role="tablist" class="nav nav-pills card-header-pills nav-justified">
                                                                            <li class="nav-item">
                                                                                <a :href="'#tab-4-'+item.id" data-bs-toggle="tab"
                                                                                   class="nav-link active">{{ $t('global.ReturnDetails') }}</a>
                                                                            </li>
                                                                            <li class="nav-item" v-if="item.purchase">
                                                                                <a :href="'#tab-5-'+item.id" data-bs-toggle="tab" class="nav-link">{{ $t('global.ShowPurchaseInvoice') }}</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="tab-content pt-0">
                                                                        <div role="tabpanel" :id="'tab-4-'+item.id" class="tab-pane fade active show">
                                                                            <loader v-if="loading"/>
                                                                            <div class="row justify-content-between">
                                                                                <div class="col-5">
                                                                                    <button @click="printData(item.id)" class="btn btn-secondary print-button head-button">
                                                                                        {{$t('global.Print')}}
                                                                                        <i class="fa fa-print"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive" :id="'printData-'+item.id">
                                                                                <div class="head-data row">

                                                                                    <div class="col-md-6 invoice-head">
                                                                                        <h5>{{$t('global.InvoiceReturnNumber')}} : {{item.id}}</h5>
                                                                                    </div>
                                                                                    <div class="col-md-6 image-div">
                                                                                        <img src="/web/img/logo.png" alt="Logo">
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <p v-if="item.purchase_id">{{$t('global.PurchaseInvoiceNumber')}} : {{item.purchase_id}}</p>
                                                                                        <p>{{$t('global.Date')}} : {{dateFormat(item.created_at)}}</p>
                                                                                        <p>{{$t('global.Supplier')}} : {{ item.sender_name }}</p>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <p v-if="item.note">{{$t('global.NotesReturn')}} : {{ item.note ?? ''}} </p>
                                                                                        <p>{{$t('global.TotalPrice')}} : {{ item.total_price }} ج.م</p>
                                                                                    </div>

                                                                                </div>

                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ $t('global.Products') }}</th>
                                                                                        <th>{{ $t('global.TotalAccount') }}</th>
                                                                                        <th>{{ $t('global.Price') }}</th>
                                                                                        <th>{{ $t('global.Partial') }}</th>
                                                                                        <th>{{ $t('global.Price') }}</th>
                                                                                        <th>{{ $t('global.TotalPrice') }}</th>
                                                                                        <th>{{ $t('global.ReturnReason') }}</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="(it,index) in item.return_products" v-if="item.return_products" :key="it.id">
                                                                                        <td>{{ index +1}}</td>
                                                                                        <td>{{ it.product.name }}</td>
                                                                                        <td>{{ it.quantity > 0 ? it.quantity : '-' }}</td>
                                                                                        <td>{{ it.quantity > 0 ? it.price+' ج.م ' : '-' }}</td>
                                                                                        <td>{{ it.sub_quantity > 0 ? it.sub_quantity : '-' }}</td>
                                                                                        <td>{{ it.sub_quantity > 0 ? parseFloat(it.sub_price).toFixed(2)+' ج.م ' : '-' }}</td>
                                                                                        <td>{{ parseFloat(it.quantity * it.price) + parseFloat(it.sub_quantity * it.sub_price) }} ج.م</td>
                                                                                        <td>{{ it.note ?? '---' }}</td>
                                                                                    </tr>
                                                                                    <tr v-else>
                                                                                        <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                                                    </tr>

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                        </div>
                                                                        <div role="tabpanel" :id="'tab-5-'+item.id" v-if="item.purchase" class="tab-pane fade">
                                                                            <loader v-if="loading"/>
                                                                            <div class="table-responsive">
                                                                                <div class="head-data row">

                                                                                    <div class="col-md-6 invoice-head">
                                                                                        <h5>{{$t('global.PurchaseInvoiceNumber')}} : {{item.purchase_id}}</h5>
                                                                                    </div>
                                                                                    <div class="col-md-6 image-div">
                                                                                        <img src="/web/img/logo.png" alt="Logo">
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <p>{{$t('global.DateOrder')}} : {{item.purchase.created_at ? dateFormat(item.purchase.created_at) : '--'}}</p>
                                                                                        <p>{{$t('global.paid up')}} : {{ item.purchase.price }} ج.م</p>
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <p>{{$t('global.Supplier')}} : {{ item.purchase.sender_name }}</p>
                                                                                        <p>{{$t('global.TotalProductPrice')}} : {{ item.purchase.product_price }} ج.م</p>
                                                                                    </div>

                                                                                </div>

                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ $t('global.Products') }}</th>
                                                                                        <th>{{ $t('global.TotalAccount') }}</th>
                                                                                        <th>{{ $t('global.Price') }}</th>
                                                                                        <th>{{ $t('global.Partial') }}</th>
                                                                                        <th>{{ $t('global.Price') }}</th>
                                                                                        <th>{{ $t('global.TotalPrice') }}</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="(it,index) in item.purchase.purchase_products" v-if="item.purchase.purchase_products" :key="it.id">
                                                                                        <td>{{ index +1}}</td>
                                                                                        <td>{{ it.product.name }}</td>
                                                                                        <td>{{ it.quantity > 0 ? it.quantity : '-' }}</td>
                                                                                        <td>{{ it.quantity > 0 ? it.price + ' ج.م ' : '-'}}</td>
                                                                                        <td>{{ it.sub_quantity > 0 ? it.sub_quantity : '-'}}</td>
                                                                                        <td>{{ it.sub_quantity > 0 ? parseFloat(it.price / it.count_unit).toFixed(2) + ' ج.م ' : '-' }}</td>
                                                                                        <td>{{ parseFloat(it.quantity * it.price) + parseFloat(it.sub_quantity * (it.price / it.count_unit)) }} ج.م</td>
                                                                                    </tr>
                                                                                    <tr v-else>
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

            <!-- start Pagination -->
            <Pagination  :limit="2" :data="purchasesPaginate" @pagination-change-page="getIncome">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref, computed, reactive, toRefs} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";
import useVuelidate from "@vuelidate/core";
import {minLength, numeric, required} from "@vuelidate/validators";
import {notify} from "@kyvg/vue3-notification";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let purchases = ref([]);
        let suppliers = ref([]);
        let treasuries = ref([]);
        let treasury_amount = ref(0);
        let fromDate = ref('');
        let toDate = ref('');
        let supplier_id = ref('');
        let loading = ref(false);
        const search = ref('');
        let purchasesPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/purchaseReturn?page=${page}&supplier_id=${supplier_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    purchasesPaginate.value = l.purchases;
                    purchases.value = l.purchases.data;
                    treasuries.value = l.treasuries;
                    treasury_amount.value = l.treasuries[0].amount;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        };

        let getSuppliers = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeSupplier`)
                .then((res) => {
                    let l = res.data.data;
                    suppliers.value = l.suppliers;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        };


        onMounted(() => {
            getIncome();
            getSuppliers();
        });
        let addJob =  reactive({
            data:{
                treasury_id:1,
                purchase_return_id:'',
                type_invoice:1,
                name_supplier:'',
                is_supplier:0,
                sender_id:0,
                treasury_amount:0,
                sender_amount:0,
            },
        });

        const rules = computed(() => {
            return {
                treasury_id:{
                    required
                },
                type_invoice:{
                    required
                },
                treasury_amount:{
                    required,
                    numeric
                },
                sender_amount:{
                    required,
                    numeric
                },
            }
        });

       let dataCash = (id) =>{
           let v = purchases.value.filter((el)=> el.id == id);
           addJob.data.name_supplier = v[0].sender_name ;
           addJob.data.purchase_return_id = id ;
           addJob.data.treasury_amount = v[0].total_price ;
           addJob.data.type_invoice = 1;
           if (v[0].supplier_id){
               addJob.data.is_supplier = 1 ;
               addJob.data.sender_id = v[0].supplier_id ;
           }else {
               addJob.data.is_supplier = 0 ;
               addJob.data.sender_id = v[0].client_id ;
           }
       };

        const v$ = useVuelidate(rules,addJob.data);

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });

        watch(()=>addJob.data.type_invoice,(after,before) =>{
            let v = purchases.value.filter((el)=> el.id == addJob.data.purchase_return_id);
            addJob.data.sender_amount = 0 ;
            addJob.data.treasury_amount = 0 ;
            if (after == 1){
                addJob.data.treasury_amount = v[0].total_price;
            }else if(after == 0) {
                addJob.data.sender_amount = v[0].total_price;
            }else{
                addJob.data.treasury_amount = v[0].total_price;
            }
        });

        watch(()=>addJob.data.treasury_amount,(after,before) =>{
            let v = purchases.value.filter((el)=> el.id == addJob.data.purchase_return_id);
            addJob.data.sender_amount =  v[0].total_price - addJob.data.treasury_amount;
        });

        watch(()=>addJob.data.sender_amount,(after,before) =>{
            let v = purchases.value.filter((el)=> el.id == addJob.data.purchase_return_id);
            addJob.data.treasury_amount =  v[0].total_price - addJob.data.sender_amount;
        });

        watch(()=>addJob.data.treasury_id,(after,before) =>{
            let v = treasuries.value.filter((el)=> el.id == addJob.data.treasury_id);
            treasury_amount.value = v[0].amount;
        });

        let close=(id)=>{
            document.getElementById('close-'+id).click();
        }

        let dateFormat = (item) => {
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        }

        let printData = (id) => {
            var printContents = document.getElementById('printData-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function deletePurchaseReturn(id, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/purchaseReturn/${id}`)
                        .then((res) => {
                            purchases.value.splice(index, 1);

                            Swal.fire({
                                icon: 'success',
                                title: `${t("global.DeletedSuccessfully")}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotDelete')}`,
                            });
                        });
                }
            });
        }

        return {purchasesPaginate,dataCash,suppliers,search,deletePurchaseReturn,treasuries,treasury_amount,permission,supplier_id, ...toRefs(addJob),v$,fromDate,toDate,purchases, loading,dateFormat,printData, getIncome,close,t};

    },methods: {
        storeJob(){
            this.v$.$validate();
            if(!this.v$.$error){
                this.loading = true;
                this.errors = {};
                adminApi.post(`/v1/dashboard/purchaseReturnAccount`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.cashReceivedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        document.getElementById('close-'+this.data.purchase_return_id).click();
                        this.resetForm();
                        this.getIncome();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        // console.log(err);
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.treasury_id = 1;
        }
    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
            errors:{}
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 100%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}


.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.submit-margin{
    margin-top: 38px !important;
}
.date-input{
    width: 175px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}
.total-head{
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background-color: #fcb00c !important;
    border-radius: 10px;
}
.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}
.head-data-table{
    display: flex;
    width: 100%;
    justify-content: space-around;
}
.head-button{
    margin-top: 5px;
    width: 200px;
    margin-bottom: 5px;
}

/*======== print ===========*/

.head-data{
    margin: 0px 0 15px 0;
}
.image-div{
    display: flex;
    flex-direction: row-reverse;
}
.image-div img {
    width: 35%;
}
.invoice-head{
    display: flex;
    align-items: center;
}

</style>
