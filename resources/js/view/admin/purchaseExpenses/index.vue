<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.purchaseExpenses') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.purchaseExpenses') }}</li>
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
                                            <div class="form-group row justify-content-around">

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
                                                    <label >{{$t('global.PurchaseInvoiceNumber')}}</label>
                                                    <input type="number" class="form-control date-input"
                                                           v-model="purchase_id">
                                                </div>

                                                <div class="col-md-2 p-0">
                                                    <label>{{ $t('global.InvoiceResourceType') }}</label>
                                                    <select v-model="is_suppler" class="form-control date-input">
                                                        <option value="0">{{$t('global.client')}}</option>
                                                        <option value="1">{{$t('global.Supplier')}}</option>
                                                    </select>
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
                                    <div class="col-md-2 row justify-content-end"></div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ $t('global.Supplier') }}</th>
                                            <th>{{$t('global.PurchaseInvoiceNumber')}}</th>
                                            <th>{{ $t('global.Amount') }}</th>
                                            <th>{{ $t('global.ProcessWriter') }}</th>
                                            <th>{{ $t('global.Date_Pay') }}</th>
                                            <th>{{ $t('global.Treasury') }}</th>
                                            <th>{{ $t('global.Notes') }}</th>
                                            <th>{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="purchases.length">
                                        <tr v-for="(item,index) in purchases"  :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ is_suppler ==1 ? item.supplier.name_supplier : item.client.name}}</td>
                                            <td>{{ item.purchase_id }}</td>
                                            <td>{{ item.amount }}</td>
                                            <td>{{ item.user.name }}</td>
                                            <td>{{  dateFormat(item.payment_date) }}</td>
                                            <td>{{ item.treasury.name }}</td>
                                            <td>{{ item.notes ?? "---" }}</td>

                                            <td>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                   :data-bs-target="'#edit-category'+item.id">
                                                    <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                                </a>
                                            </td>

                                            <!-- Edit Modal -->
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
                                                                            </div>
                                                                            <div class="table-responsive" :id="'printData-'+item.id">
                                                                                <div class="head-data row">

                                                                                    <div class="col-md-6 invoice-head">
                                                                                        <h5>{{$t('global.InvoiceNumber')}} : {{item.purchase.id}}</h5>
                                                                                    </div>
                                                                                    <div class="col-md-6 image-div">
                                                                                        <img src="/web/img/logo.png" alt="Logo">
                                                                                    </div>


                                                                                    <div class="col-md-6">
                                                                                        <p>{{$t('global.DateOrder')}} : {{dateFormat(item.purchase.created_at)}}</p>
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
                                                                                        <td>{{ it.quantity }}</td>
                                                                                        <td>{{ it.price }} ج.م</td>
                                                                                        <td>{{ it.sub_quantity }}</td>
                                                                                        <td>{{ parseFloat(it.price / it.count_unit).toFixed(2) }} ج.م</td>
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
                                            <th class="text-center" colspan="9">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- start Pagination -->
            <Pagination :limit="2" :data="purchasesPaginate" @pagination-change-page="getIncome">
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
import {onMounted, inject, watch, ref, computed} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let purchases = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let purchase_id = ref('');
        let is_suppler = ref(1);
        let loading = ref(false);
        const search = ref('');
        let purchasesPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;
            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }
            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

            adminApi.get(`/v1/dashboard/purchaseExpenses?page=${page}&search=${search.value}&from_date=${fromDate.value}&to_date=${toDate.value}&purchase_id=${purchase_id.value}&is_suppler=${is_suppler.value}`)
                .then((res) => {
                    let l = res.data.data;
                    purchasesPaginate.value = l.purchases;
                    purchases.value = l.purchases.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }

        onMounted(() => {
            getIncome();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
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
            let printContents = document.getElementById('printData-'+id).innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {purchasesPaginate,search,permission,is_suppler,purchase_id,fromDate,toDate,purchases, loading,dateFormat,printData, getIncome,close};

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
