<template>

    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.IncomeAndExpense') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.IncomeAndExpense') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->
            <div class="card bg-white projects-card">
                <div class="card-body pt-0">
                    <div class="card-header">
                        <h5 class="card-title">{{ $t('global.Recording of financial transactions') }}</h5>
                    </div>
                    <div class="reviews-menu-links">
                        <ul role="tablist" class="nav nav-pills card-header-pills nav-justified">
                            <li class="nav-item">
                                <a href="#tab-4" data-bs-toggle="tab"
                                   class="nav-link active">{{ $t('global.Incomes') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-5" data-bs-toggle="tab" class="nav-link">{{ $t('global.Expenses') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content pt-0">
                        <div role="tabpanel" id="tab-4" class="tab-pane fade active show">
                            <loader v-if="loading"/>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    {{ $t('global.Search') }}:
                                    <input type="search" v-model="searchIncome" class="custom"/>
                                </div>
                                <div class="col-5 row justify-content-end">
                                    <router-link
                                        :to="{name: 'createIncomeData', params: {lang: locale || 'ar'}}"
                                        v-if="permission.includes('income&expense create')"
                                        class="btn btn-custom btn-warning">
                                        {{ $t('global.Add') }}
                                    </router-link>
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
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="incomes.length">
                                    <tr v-for="(item,index) in incomes" :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.income.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.payer }}</td>
                                        <td>{{ item.notes }}</td>

                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>

                                        <td>

                                            <router-link
                                                :to="{name: 'editIncomeData', params: {lang: locale || 'ar',id:item.id}}"
                                                v-if="permission.includes('income&expense edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteIncome(item.id,index)"
                                               v-if="permission.includes('income&expense delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                               :data-bs-target="'#edit-category'+item.id">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                        <!-- Edit Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.PrintReceiptVoucher') }}</h4>
                                                        <button  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="col-md-6 ">
                                                            <div class="form-group col-lg-12">
                                                                <img src="/web/img/logo.png">
                                                                <hr class="hr-show">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group col-lg-12 text-center">
                                                                <h5>{{ $t('global.ReceiptVoucherNumber') }} :
                                                                    {{ item.id }}</h5>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mt-3">
                                                            <div class="row">
                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.WeReceivedFromMr') }} :
                                                                        {{ item.payer }}</h5>
                                                                </div>

                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.Amount') }} : {{
                                                                            item.amount
                                                                        }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.Date') }} :
                                                                        {{ item.payment_date }}</h5>
                                                                </div>

                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.For') }} : {{ item.notes }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group col-md-6 make-border ">
                                                                    <h5 class="mt-2 mb-2">{{
                                                                            $t('global.RecipientsSignature')
                                                                        }} : </h5>
                                                                </div>
                                                                <div class="form-group col-md-6 make-border ">
                                                                    <h5 class="mt-2 mb-2">{{
                                                                            $t('global.TreasurersSignature')
                                                                        }} : </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <button @click="printReset" class="btn btn-success print-button">
                                                                        {{$t('global.Print')}}
                                                                        <i class="fa fa-print"></i>
                                                                    </button>
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
                                    <input type="search" v-model="searchExpense" class="custom"/>
                                </div>
                                <div class="col-5 row justify-content-end">
                                    <router-link
                                        :to="{name: 'createExpenseData', params: {lang: locale || 'ar'}}"
                                        v-if="permission.includes('income&expense create')"
                                        class="btn btn-custom btn-warning">
                                        {{ $t('global.Add') }}
                                    </router-link>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-center table-hover mb-0 datatable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Band Name') }}</th>
                                        <th>{{ $t('global.Amount') }}</th>
                                        <th>{{ $t('global.RecipientsName') }}</th>
                                        <th>{{ $t('global.For') }}</th>

                                        <th>{{ $t('global.PaymentDate') }}</th>
                                        <th>{{ $t('global.TransactionDate') }}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="expenses.length">
                                    <tr v-for="(item,index) in expenses" :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.expense.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.payer }}</td>
                                        <td>{{ item.notes }}</td>

                                        <td>{{ item.payment_date }}</td>
                                        <td>{{ dateFormat(item.created_at) }}</td>
                                        <td>{{ item.user ? item.user.name : "-----" }}</td>
                                        <td>
                                            <router-link
                                                v-if="permission.includes('income&expense edit')"
                                                :to="{name: 'editExpenseData', params: {lang: locale || 'ar',id:item.id}}"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteExpense(item.id,index)"
                                            v-if="permission.includes('income&expense delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal"
                                               :data-bs-target="'#edit-category'+item.id">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                        <!-- Edit Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="printExpense">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.VoucherPrinting') }}</h4>
                                                        <button  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="col-md-6 ">
                                                            <div class="form-group col-lg-12">
                                                                <img src="/web/img/logo.png">
                                                                <hr class="hr-show">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group col-lg-12 text-center">
                                                                <h5>{{ $t('global.VoucherNumber') }} :
                                                                    {{ item.id }}</h5>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 mt-3">
                                                            <div class="row">
                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.SendToMr') }} :
                                                                        {{ item.payer }}</h5>
                                                                </div>

                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.Amount') }} : {{
                                                                            item.amount
                                                                        }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.Date') }} :
                                                                        {{ item.payment_date }}</h5>
                                                                </div>

                                                                <div class="form-group col-lg-12 col-md-12">
                                                                    <h5>{{ $t('global.For') }} : {{ item.notes }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group col-md-6 make-border ">
                                                                    <h5 class="mt-2 mb-2">{{
                                                                            $t('global.RecipientsSignature')
                                                                        }} : </h5>
                                                                </div>
                                                                <div class="form-group col-md-6 make-border ">
                                                                    <h5 class="mt-2 mb-2">{{
                                                                            $t('global.TreasurersSignature')
                                                                        }} : </h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="form-group col-md-6">
                                                                    <button @click="printExpense" class="btn btn-success print-button">
                                                                        {{$t('global.Print')}}
                                                                        <i class="fa fa-print"></i>
                                                                    </button>
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
                            <!-- start Pagination -->
                            <Pagination :limit="2" :data="expensesPaginate" @pagination-change-page="getExpense">
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
import {onMounted, inject, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});

        // get income
        let incomes = ref([]);
        let incomesPaginate = ref({});
        let loading = ref(false);
        const searchIncome = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        //get expense
        let expenses = ref([]);
        let expensesPaginate = ref({});
        let setting = ref({});
        const searchExpense = ref('');

        let getIncome = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/calcIncome?page=${page}&search=${searchIncome.value}`)
                .then((res) => {
                    let l = res.data.data;
                    incomesPaginate.value = l.calcIncome;
                    incomes.value = l.calcIncome.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        let getSetting = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/setting`)
                .then((res) => {
                    let l = res.data.data;
                    setting.value = l.setting[0];
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        watch(searchIncome, (searchIncome, prevSearch) => {
            if (searchIncome.length >= 0) {
                getIncome();
            }
        });

        //get expense

        let getExpense = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/calcExpense?page=${page}&search=${searchExpense.value}`)
                .then((res) => {
                    let l = res.data.data;
                    expensesPaginate.value = l.calcExpense;
                    expenses.value = l.calcExpense.data;
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
            getExpense();
            getSetting();
        });

        emitter.on('get_lang', () => {
            getIncome();
            getExpense();
            getSetting();
        });

        watch(searchExpense, (searchExpense, prevSearch) => {
            if (searchExpense.length >= 0) {
                getExpense();
            }
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

        let printReset = () => {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
        let printExpense = () => {
            var printContents = document.getElementById('printExpense').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function deleteIncome(id, index) {
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

                    adminApi.delete(`/v1/dashboard/incomeAndExpense/${id}`)
                        .then((res) => {
                            incomes.value.splice(index,1);

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

        function deleteExpense(id, index) {
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

                    adminApi.delete(`/v1/dashboard/incomeAndExpense/${id}`)
                        .then((res) => {
                            expenses.value.splice(index,1);

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

        return {
            printExpense,
            printReset,
            setting,
            dateFormat,
            incomes,
            loading,
            getIncome,
            searchIncome,
            deleteIncome,
            incomesPaginate,
            searchExpense,
            getExpense,
            expenses,
            permission,
            expensesPaginate,
            deleteExpense
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

.hover:hover {
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.modal-dialog {
    max-width: 700px !important;
}

.hr-show {
    color: #fcb00c;
    height: 5px;
}

.show-price {
    display: flex;
    justify-content: center;
}

.show-price h5 {
    margin: 0px 5px 0px 5px;
}

.custom-modal .close span {
    top: 0 !important;
}

.form-group img {
    width: 35% !important;
}

.border-left {
    border-left: 2px solid #000;
}

.border-right-non {
    border-right: none !important;
}

.make-border {
    border: 2px solid #000000;
}
@media print {
    .print-button {
        display: none;
    }
}

</style>
