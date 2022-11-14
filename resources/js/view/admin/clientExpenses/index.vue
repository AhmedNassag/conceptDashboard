<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.clientExpenses') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.clientExpenses') }}</li>
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

                                                <div class="col-md-4">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="fromDate">
                                                </div>

                                                <div class="col-md-4">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="toDate">
                                                </div>

                                                <div class="col-md-4">
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
                                        <router-link
                                            :to="{name: 'createClientExpenses'}"
                                            v-if="permission.includes('clientExpenses create')"
                                            class="btn btn-warning">
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
                                            <th>{{ $t('global.client') }}</th>
                                            <th>{{$t('global.ExpenseItem')}}</th>
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
                                            <td>{{ item.client.name }}</td>
                                            <td>{{ item.expense.name }}</td>
                                            <td>{{ item.amount }}</td>
                                            <td>{{ item.user.name }}</td>
                                            <td>{{  dateFormat(item.payment_date) }}</td>
                                            <td>{{ item.treasury.name }}</td>
                                            <td>{{ item.notes ?? "---" }}</td>

                                            <td>
                                                <router-link
                                                    :to="{name: 'editClientExpenses', params: {id:item.id}}"
                                                    v-if="permission.includes('clientExpenses edit') && item.client_account"
                                                    class="btn btn-sm btn-success me-2">
                                                    <i class="far fa-edit"></i>
                                                </router-link>

                                                <a href="#" @click="deleteJob(item.id,index)"
                                                   v-if="permission.includes('clientExpenses delete') && item.client_account"
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
                                                    <div class="modal-content" :id="'printData-'+item.id">

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
                                                                            {{ item.client.name }}</h5>
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
                                                                        <button @click="printData(item.id)" class="btn btn-success print-button">
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
        let loading = ref(false);
        const search = ref('');
        let purchasesPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/clientExpense?page=${page}&search=${search.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
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

        function deleteJob(id, index) {
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

                    adminApi.delete(`/v1/dashboard/clientExpense/${id}`)
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

        return {purchasesPaginate,search,permission,deleteJob,fromDate,toDate,purchases, loading,dateFormat,printData, getIncome,close};

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

.amount h5{
    font-weight: 700;
    text-align: center;
}

.date-input{
    width: 175px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
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
/*======== print ===========*/

.image-div img {
    width: 35%;
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

.show-price h5 {
    margin: 0px 5px 0px 5px;
}

.custom-modal .close span {
    top: 0 !important;
}

.form-group img {
    width: 35% !important;
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
