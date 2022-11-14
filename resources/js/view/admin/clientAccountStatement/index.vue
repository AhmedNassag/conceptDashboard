<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.clientAccountStatement') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.clientAccountStatement') }}</li>
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
                                        <form @submit.prevent="getData" class="needs-validation">
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

                                                <div class="col-md-5 mb-3 position-relative">
                                                    <label> {{ $t('global.ChooseClient') }} </label>
                                                    <label class="balance"> {{$t('global.Balance')}} : {{balance}}</label>

                                                    <input type="text"
                                                           class="form-control mb-1 input-Sender"
                                                           v-model="searchSupplier"
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
                                                            {{ dropDownSender.name_supplier }}
                                                        </li>
                                                    </ul>

                                                    <input type="text"
                                                           :class="['form-control']"
                                                           disabled
                                                           v-model="nameSender"
                                                    >
                                                </div>

                                                <div class="col-md-1">
                                                    <button class="btn btn-primary" type="submit" v-if="isButton">{{$t('global.Submit')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-12 row mt-3">
                                        <div class="col-6 d-flex">
                                            <button @click="printRestriction" class="btn btn-info print-button" v-if="isButton">
                                                {{$t('global.Print')}}
                                                <i class="fa fa-print"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="printRestriction">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.For') }}</th>
                                        <th>{{ $t('global.RegistrationDate') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="restrictions.length">
                                        <tr v-for="(item,index) in restrictions"  :key="item.id">

                                            <td>{{ index + 1 }}</td>
                                            <td>{{ item.amount }}</td>
                                            <td v-if="item.purchase_id"> شراء منتجات رقم الفاتورة {{item.purchase_id}}</td>
                                            <td v-else-if="item.purchase_return_id"> مرتجع منتجات رقم الفاتورة {{item.purchase_return_id}}</td>
                                            <td v-else-if="item.order_id"> طلب منتجات رقم الفاتورة {{item.order_id}}</td>
                                            <td v-else-if="index == 0 && !fromDate && !toDate"> رصيد اول المده </td>
                                            <td v-else-if="item.client_expense_id"> {{item.client_expense.notes ?? '---'}} </td>
                                            <td v-else-if="item.client_income_id"> {{item.client_income.notes ?? '---'}} </td>
                                            <td v-else>---</td>
                                            <td>{{  dateFormat(item.created_at) }}</td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr >
                                            <th class="text-center" colspan="4">{{ $t('global.NoDataFound') }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :limit="2" :data="restrictionsPaginate" @pagination-change-page="getRestrictions">
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
        const emitter = inject('emitter');
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);

        // get packages
        let restrictions = ref([]);
        let accounts = ref([]);
        let restrictionsPaginate = ref({});
        let supplier_id = ref(0);
        let nameSender= ref('');
        let balance= ref(0);
        let dropDownSenders= ref([]);
        let suppliers= ref([]);
        let searchSupplier= ref('');
        let fromDate = ref('');
        let toDate = ref('');
        let loading = ref(false);
        let isButton = ref(true);

        let searchSender = () => {
            dropDownSenders.value = [];
            if(searchSupplier.value){
                let thisString = new RegExp(searchSupplier.value,'i');
                let items = suppliers.value.filter(e => e.name_supplier.match(thisString) || e.id == searchSupplier.value);
                dropDownSenders.value = items.splice(0,10);
            }else{
                dropDownSenders.value = [];
            }

            isButton.value = false;
        };

        let showSenderName = (index) => {
            let item = dropDownSenders.value[index];
            nameSender.value = item.name_supplier ;
            supplier_id.value = item.id;
            balance.value = item.sum_account;
            searchSupplier.value = '';
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
                e.target.blur();
            };

        });

        document.addEventListener('click',(e) => {
            if(dropDownSenders.value.length > 0){
                if(!e.target.classList.contains('Sender') && !e.target.classList.contains('input-Sender') && e.pointerType){
                    dropDownSenders.value = [];
                }
            }
        });

        let getData = (page = 1) => {

            loading.value = true;

            adminApi.get(`/v1/dashboard/clientAccountStatement/${supplier_id.value}?page=${page}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    restrictionsPaginate.value = l.supplierAccounts;
                    restrictions.value = l.supplierAccounts.data;
                    suppliers.value = l.suppliers;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();
        });

        watch(supplier_id, (supplier_id, prevSearch) => {
            getData();
        });

        let printRestriction = () => {
            var printContents = document.getElementById('printRestriction').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
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

        return {
            nameSender,
            balance,
            searchSupplier,
            dropDownSenders,
            suppliers,
            printRestriction,
            showSenderName,
            searchSender,
            senderHover,
            permission,
            fromDate,
            toDate,
            getData,
            restrictions,
            accounts,
            supplier_id,
            loading,
            dateFormat,
            isButton,
            restrictionsPaginate
        };
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
.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
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

.date-input{
    width: 135px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
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
.balance{
    padding: 0 25px 0 0;
}

</style>
