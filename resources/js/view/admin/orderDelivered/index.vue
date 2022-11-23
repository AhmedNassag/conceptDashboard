<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.orderDelivered') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.orderDelivered') }}</li>
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
                                        <form @submit.prevent="getOrder" class="needs-validation">
                                            <div class="form-group row align-items-center justify-content-around">

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="fromDate">
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="toDate">
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label>{{ $t('global.orderType') }}</label>
                                                    <select v-model="order_type" class="form-control date-input">
                                                        <option value="">{{$t('global.allType')}}</option>
                                                        <option value="2">{{$t('sidebar.OrderDirect')}}</option>
                                                        <option value="1">{{$t('global.orderOnline')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label >{{$t('global.sellInvoiceNumber')}}</label>
                                                    <input type="number" class="form-control date-input"
                                                           v-model="order_id">

                                                </div>

                                                <div class="col-md-2 mt-4 p-0">
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
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{$t('global.InvoiceNumber')}}</th>
                                        <th>{{ $t('sidebar.client') }}</th>
                                        <th>{{ $t('global.Store') }}</th>
                                        <th>{{ $t('global.TotalPrice') }}</th>
                                        <th>{{ $t('global.Date') }}</th>
                                        <th>{{ $t('global.orderStatus') }}</th>
                                        <th>{{ $t('global.representative') }}</th>
                                        <th>{{ $t('global.orderType') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="orders.length">
                                    <tr v-for="(item,index) in orders"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td>{{ item.store.name }}</td>
                                        <td>{{ item.total }}</td>
                                        <td>{{  dateFormat(item.created_at) }}</td>
                                        <td>{{ item.order_status.name }}</td>
                                        <td>{{ item.representative ?item.representative.name: '---' }}</td>
                                        <td>{{ parseInt(item.is_online) == 1 ? $t('global.orderOnline') : $t('sidebar.OrderDirect') }}</td>

                                        <td>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                               :data-bs-target="'#edit-category'+item.id" :title="$t('global.Print')">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            <router-link
                                                :to="{name: 'showOrderDelivered',params:{id:item.id}}"
                                                v-if="permission.includes('orderDelivered read')"
                                                class="btn btn-sm btn-info me-2" :title="$t('global.Show')">
                                                <i class="fas fa-book-open"></i>
                                            </router-link>

                                        </td>

                                        <!-- invoice big Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.InvoiceDetails') }}</h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
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
                                                                            <div class="col-4 d-flex w-25 justify-content-end">
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive" :id="'printData-'+item.id">
                                                                            <div class="head-data row">

                                                                                <div class="col-md-6 invoice-head">
                                                                                    <h5>{{$t('global.InvoiceNumber')}} : {{item.id}}</h5>
                                                                                </div>

                                                                                <div class="col-md-6 image-div">
                                                                                    <img src="/web/img/logo.png" alt="Logo">
                                                                                </div>


                                                                                <div class="col-md-6">
                                                                                    <p>{{$t('global.DateOrder')}} : {{dateFormat(item.created_at)}}</p>
                                                                                    <p>{{$t('global.store')}} : {{ item.store.name }}</p>
                                                                                    <p>
                                                                                        {{$t('global.TotalPriceBeforeDiscount')}} :
                                                                                        {{item.sub_total }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                    <p>{{$t('global.discountValue')}} : {{ parseInt(item.is_online) == 1 ? item.discount : item.discount_offer }} {{ item.currency }}</p>

                                                                                    <p v-if="item.order_other_offer">
                                                                                        {{$t('global.otherDiscount')}} :
                                                                                        {{ item.order_other_offer.offer }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <p>
                                                                                        {{$t('global.TotalPriceAfterDiscount')}} :
                                                                                        {{
                                                                                            item.order_other_offer?
                                                                                                parseFloat( item.sub_total - item.order_other_offer.offer - (parseInt(item.is_online) == 1 ? item.discount : item.discount_offer) ).toFixed(2) :
                                                                                                parseFloat( item.sub_total - (parseInt(item.is_online) == 1 ? item.discount : item.discount_offer)).toFixed(2)
                                                                                        }}
                                                                                        {{ item.currency }}
                                                                                    </p>
                                                                                    <p v-if="item.tax">{{$t('global.taxValue')}} : {{ item.tax }} {{ item.currency }}</p>
                                                                                    <p v-if="item.tax">{{$t('global.TotalPriceAfterTax')}} : {{ parseFloat(item.total- item.shippingPrice) }} {{ item.currency }}</p>
                                                                                    <p v-if="item.shippingPrice">{{$t('global.shipping')}} : {{ item.shippingPrice }} {{ item.currency }}</p>
                                                                                    <p>{{$t('global.TotalPriceAfterDiscountAndShipping')}} : {{ item.total }} {{ item.currency }}</p>
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
                                                                                <tbody v-if="item.order_details.length">
                                                                                <tr v-for="(it,index) in item.order_details" :key="it.id">
                                                                                    <td>{{ index +1}}</td>
                                                                                    <td>{{ it.product.name }}</td>
                                                                                    <td>{{ it.quantity ? it.quantity + ' ( '+it.main_measurement_unit.name+' ) ' : '-'}}</td>
                                                                                    <td>{{ it.sub_quantity ? it.sub_quantity + ' ( '+it.sub_measurement_unit.name+' ) ' : '-' }} </td>
                                                                                    <td>{{ it.quantity ? it.price : '-'}}</td>
                                                                                    <td>{{ it.sub_quantity ? it.sub_price : '-'}}</td>
                                                                                    <td>
                                                                                        {{
                                                                                            parseFloat((it.quantity * it.price) + (it.sub_quantity * it.sub_price))
                                                                                                .toFixed(2)
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
            <Pagination :limit="2" :data="ordersPaginate" @pagination-change-page="getOrder">
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
import {numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import {notify} from "@kyvg/vue3-notification";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let orders = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let order_id = ref('');
        let order_type = ref('');
        let loading = ref(false);
        const search = ref('');
        let ordersPaginate = ref({});

        let getOrder = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/orderDelivered?page=${page}&search=${search.value}&order_type=${order_type.value}&order_id=${order_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    ordersPaginate.value = l.orders;
                    orders.value = l.orders.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }

        onMounted(() => {
            getOrder();
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

        let printDataSmall = (id) => {
            var printContents = document.getElementById('printData-small-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getOrder();
            }
        });

        return {t,ordersPaginate,search,order_type,printDataSmall,permission,order_id,fromDate,toDate,orders, loading,dateFormat,printData, getOrder,close};

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin")
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
    margin-bottom: 5px;
}
.edit-envoice .modal-dialog{
    max-width: 500px !important;
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

td.description,
th.description {
    width: 150px;
    max-width: 150px;
}

td.index,
th.index {
    width: 50px;
    max-width: 50px;
}

td.quantity,
th.quantity {
    width: 50px;
    max-width: 50px;
    word-break: break-all;
}

td.price1,
th.price1 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
}

td.price2,
th.price2 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
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
