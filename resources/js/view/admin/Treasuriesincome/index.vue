<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.treasuriesIncome') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.treasuriesIncome') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <div class="card bg-white projects-card">
                <div class="card-body pt-0">
                    <div class="card-header pt-0">
                        <div class="row justify-content-between">
                            <div class="col-4 row ">
                                <select v-model="treasury_id"  class="form-select">
                                    <option value="0">{{$t('treasury.ChooseTreasury')}}</option>
                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                </select>

                            </div>
                            <div v-if="treasury_id" class="col-12 row mt-3">
                                <div class="col-4">
                                    <div class="amount">
                                        <h5>
                                            {{$t('global.NetTreasury')}} : {{amount}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="amount">
                                        <h5>
                                            {{$t('global.TotalIncome')}} : {{income}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="amount">
                                        <h5>
                                            {{$t('global.TotalExpense')}} : {{expense}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="amount">
                                        <h5>
                                            {{$t('global.incomeTransfer')}} : {{income_transfer}}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="amount">
                                        <h5>
                                            {{$t('global.expenseTransfer')}} : {{expense_transfer}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-menu-links">
                        <ul role="tablist" class="nav nav-pills card-header-pills nav-justified">
                            <li class="nav-item">
                                <a href="#tab-4" data-bs-toggle="tab"
                                   class="nav-link active">{{ $t('global.IncomeItems') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-5" data-bs-toggle="tab" class="nav-link">{{ $t('global.clientsIncome') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-6" data-bs-toggle="tab" class="nav-link">{{ $t('global.SuppliersIncome') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-7" data-bs-toggle="tab" class="nav-link">{{ $t('global.capitalOwnerAccount') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content pt-0">
                        <div role="tabpanel" id="tab-4" class="tab-pane fade active show">
                            <loader v-if="loading"/>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    {{ $t('global.Search') }}:
                                    <input type="search" v-model="search" class="custom"/>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.NameOfThePayer') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="incomes.length">
                                    <tr v-for="(item,index) in incomes" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.income.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.payer }}</td>
                                        <td>{{ item.notes }}</td>
                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="8">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- start Pagination -->
                            <Pagination :limit="2" :data="incomesPaginate" @pagination-change-page="getIncome">
                                <template #prev-nav>
                                    <span>&lt; {{ $t('global.Previous') }}</span>
                                </template>
                                <template #next-nav>
                                    <span>{{ $t('global.Next') }} &gt;</span>
                                </template>
                            </Pagination>
                            <!-- end Pagination -->
                        </div>
                        <div role="tabpanel" id="tab-5" class="tab-pane fade">
                            <loader v-if="loading"/>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    {{ $t('global.Search') }}:
                                    <input type="search" v-model="searchClientIncomes" class="custom"/>
                                </div>
                                <div class="col-5 row justify-content-end">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.NameOfThePayer') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="clientIncomes.length">
                                    <tr v-for="(item,index) in clientIncomes" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.income.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.client.name }}</td>
                                        <td>{{ item.notes ?? '---' }}</td>
                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="8">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- start Pagination -->
                            <Pagination :limit="2" :data="clientIncomesPaginate" @pagination-change-page="getClientIncomes">
                                <template #prev-nav>
                                    <span>&lt; {{ $t('global.Previous') }}</span>
                                </template>
                                <template #next-nav>
                                    <span>{{ $t('global.Next') }} &gt;</span>
                                </template>
                            </Pagination>
                            <!-- end Pagination-->
                        </div>
                        <div role="tabpanel" id="tab-6" class="tab-pane fade">
                            <loader v-if="loading"/>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    {{ $t('global.Search') }}:
                                    <input type="search" v-model="searchSupplierIncomes" class="custom"/>
                                </div>
                                <div class="col-5 row justify-content-end">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.NameOfThePayer') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="supplierIncomes.length">
                                    <tr v-for="(item,index) in supplierIncomes" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.income.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.supplier.name_supplier }}</td>
                                        <td>{{ item.notes ?? '---' }}</td>
                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="8">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- start Pagination -->
                            <Pagination :limit="2" :data="supplierIncomesPaginate" @pagination-change-page="getSupplierIncomes">
                                <template #prev-nav>
                                    <span>&lt; {{ $t('global.Previous') }}</span>
                                </template>
                                <template #next-nav>
                                    <span>{{ $t('global.Next') }} &gt;</span>
                                </template>
                            </Pagination>
                            <!-- end Pagination-->
                        </div>
                        <div role="tabpanel" id="tab-7" class="tab-pane fade">
                            <loader v-if="loading"/>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    {{ $t('global.Search') }}:
                                    <input type="search" v-model="searchOwnerIncomes" class="custom"/>
                                </div>
                                <div class="col-5 row justify-content-end">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.NameOfThePayer') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="ownerIncomes.length">
                                    <tr v-for="(item,index) in ownerIncomes" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.income.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.notes ?? '---' }}</td>
                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="8">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- start Pagination -->
                            <Pagination :limit="2" :data="ownerIncomesPaginate" @pagination-change-page="getOwnerIncomes">
                                <template #prev-nav>
                                    <span>&lt; {{ $t('global.Previous') }}</span>
                                </template>
                                <template #next-nav>
                                    <span>{{ $t('global.Next') }} &gt;</span>
                                </template>
                            </Pagination>
                            <!-- end Pagination-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, inject, watch, ref} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});

        // get packages
        let incomes = ref([]);
        let treasuries = ref([]);
        let incomesPaginate = ref({});
        let treasury_id = ref(0);
        let amount = ref(0);
        let income = ref(0);
        let expense = ref(0);
        let income_transfer = ref(0);
        let expense_transfer = ref(0);
        let loading = ref(false);
        const search = ref('');
        const searchClientIncomes = ref('');
        const searchSupplierIncomes = ref('');
        const searchOwnerIncomes = ref('');
        let clientIncomes = ref([]);
        let clientIncomesPaginate = ref({});
        let supplierIncomes = ref([]);
        let supplierIncomesPaginate = ref({});
        let ownerIncomes = ref([]);
        let ownerIncomesPaginate = ref({});

        let getIncome = (page = 1) => {
           if (treasury_id.value > 0){
               loading.value = true;

               adminApi.get(`/v1/dashboard/treasuriesIncome/${treasury_id.value}?page=${page}&search=${search.value}`)
                   .then((res) => {
                       let l = res.data.data;
                       incomesPaginate.value = l.incomes;
                       incomes.value = l.incomes.data;
                   })
                   .catch((err) => {
                       console.log(err.response.data);
                   })
                   .finally(() => {
                       loading.value = false;
                   });
           }
        }

        let getClientIncomes = (page = 1) => {
            loading.value = true;
            if (treasury_id.value > 0) {
                adminApi.get(`/v1/dashboard/treasuriesClientIncome/${treasury_id.value}?page=${page}&search=${searchClientIncomes.value}`)
                    .then((res) => {
                        let l = res.data.data;

                        clientIncomesPaginate.value = l.clientIncomes;
                        clientIncomes.value = l.clientIncomes.data;
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                    })
                    .finally(() => {
                        loading.value = false;
                    })
            }
        }
        let getSupplierIncomes = (page = 1) => {
            loading.value = true;
            if (treasury_id.value > 0) {
                adminApi.get(`/v1/dashboard/treasuriesSupplierIncome/${treasury_id.value}?page=${page}&search=${searchSupplierIncomes.value}`)
                    .then((res) => {
                        let l = res.data.data;
                        supplierIncomesPaginate.value = l.supplierIncomes;
                        supplierIncomes.value = l.supplierIncomes.data;
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                    })
                    .finally(() => {
                        loading.value = false;
                    })
            }
        }
        let getOwnerIncomes = (page = 1) => {
            loading.value = true;
            if (treasury_id.value > 0) {
                adminApi.get(`/v1/dashboard/treasuriesOwnerIncomes/${treasury_id.value}?page=${page}&search=${searchOwnerIncomes.value}`)
                    .then((res) => {
                        let l = res.data.data;
                        ownerIncomesPaginate.value = l.ownerIncomes;
                        ownerIncomes.value = l.ownerIncomes.data;
                    })
                    .catch((err) => {
                        console.log(err.response.data);
                    })
                    .finally(() => {
                        loading.value = false;
                    })
            }
        }
        let getTreasuries = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeTreasury`)
                .then((res) => {
                    let l = res.data.data;
                    treasuries.value= l.treasuries;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getIncome();
            getTreasuries();
            getClientIncomes();
            getSupplierIncomes();
            getOwnerIncomes();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });

        watch(searchClientIncomes, (searchClientIncomes, prevSearch) => {
            if (searchClientIncomes.length >= 0) {
                getClientIncomes();
            }
        });

        watch(searchSupplierIncomes, (searchSupplierIncomes, prevSearch) => {
            if (searchSupplierIncomes.length >= 0) {
                getSupplierIncomes();
            }
        });

        watch(searchOwnerIncomes, (searchOwnerIncomes, prevSearch) => {
            if (searchOwnerIncomes.length >= 0) {
                getOwnerIncomes();
            }
        });

        watch(treasury_id, (treasury_id, prevSearch) => {
            let v = treasuries.value.find((el)=> el.id == treasury_id)

            amount.value = v.amount;
            income.value = v.income;
            expense.value = v.expense;
            income_transfer.value = v.income_transfer;
            expense_transfer.value = v.expense_transfer;

            getIncome();
            getClientIncomes();
            getSupplierIncomes();
            getOwnerIncomes();
        });


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

        return {expense,getClientIncomes,getSupplierIncomes,getOwnerIncomes,ownerIncomesPaginate,ownerIncomes,searchOwnerIncomes,searchClientIncomes,searchSupplierIncomes,clientIncomesPaginate,supplierIncomesPaginate,clientIncomes,supplierIncomes,income,amount,income_transfer,expense_transfer,treasury_id,incomes,treasuries, loading, getIncome,dateFormat, search, incomesPaginate};

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
    width: 30%;
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


.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
    margin-top: 3px;
}
.amount h5{
    text-align: center;
}

</style>
