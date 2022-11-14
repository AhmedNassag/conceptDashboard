<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.DailyRestriction') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.DailyRestriction') }}</li>
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

                                                <div class="col-md-3">
                                                    <label >{{$t('global.AccountingEntryNumber')}}</label>
                                                    <input type="number" class="form-control date-input"
                                                           v-model="treasury_id">

                                                </div>

                                                <div class="col-md-3 mt-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="total-head">
                                                    <h4>{{$t('global.TotalDebit')}} : {{totalDebit}}</h4>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="total-head">
                                                    <h4>{{$t('global.TotalCreditor')}} : {{totalCreditor}}</h4>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-2 row justify-content-end">
                                        <router-link v-if="permission.includes('DailyRestriction create')"
                                            :to="{name: 'createDailyRestriction', params: {lang: locale || 'ar'}}"
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
                                            <th>{{$t('global.AccountingEntryNumber')}}</th>
                                            <th>{{ $t('global.Description') }}</th>
                                            <th>{{ $t('global.Amount') }}</th>
                                            <th>{{ $t('global.FromAccount') }}</th>
                                            <th>{{ $t('global.ToAccount') }}</th>
                                            <th>{{ $t('global.Date') }}</th>
                                            <th>{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="dailies.length">
                                        <tr v-for="(item,index) in dailies"  :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.desc }}</td>
                                            <td>{{ item.amount }}</td>
                                            <td>
                                                <span v-for="it in item.restriction" v-if="item.restriction" :key="it.id">
                                                    <span v-if="it.debit == 1" class="d-block">{{it.account_name}}</span>
                                                </span>
                                            </td>
                                            <td>
                                                <span v-for="it in item.restriction" v-if="item.restriction" :key="it.id">
                                                    <span v-if="it.debit == 0" class="d-block">{{it.account_name}}</span>
                                                </span>
                                            </td>

                                            <td>{{ item.date }}</td>

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
                                                                {{ $t('global.AccountingEntry') }}</h4>
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
                                                                                   class="nav-link active">{{ $t('global.AccountingEntry') }}</a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a :href="'#tab-5-'+item.id" data-bs-toggle="tab" class="nav-link">{{ $t('global.AccountingEntryRecord') }}</a>
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
                                                                                <div class="col-5 row justify-content-end">
                                                                                    <router-link @click="close(item.id)" v-if="permission.includes('DailyRestriction edit')"
                                                                                        :to="{name: 'editDailyRestriction', params: {lang: locale || 'ar',id:item.id}}"
                                                                                        class="btn btn-sm btn-success me-2 head-button">
                                                                                        <i class="far fa-edit"></i> {{$t('global.Edit')}}
                                                                                    </router-link>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive" :id="'printData-'+item.id">
                                                                                <div class="head-data-table">
                                                                                    <p>{{$t('global.Number')}} : {{item.id}}</p>
                                                                                    <p>{{$t('global.RegistrationDate')}} : {{item.date}}</p>
                                                                                </div>

                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ $t('global.AccountName') }}</th>
                                                                                        <th>{{ $t('global.Description') }}</th>
                                                                                        <th>{{ $t('global.Debit') }}</th>
                                                                                        <th>{{ $t('global.Creditor') }}</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="(it,index) in item.restriction" v-if="item.restriction" :key="it.id">
                                                                                        <td>{{ index +1}}</td>
                                                                                        <td>{{ it.account_name }}</td>
                                                                                        <td>{{ it.description }}</td>
                                                                                        <td> <span v-if="it.debit == 1">{{ it.amount }}</span> <span v-else >---</span></td>
                                                                                        <td> <span v-if="it.debit == 0">{{ it.amount }}</span> <span v-else >---</span></td>

                                                                                    </tr>
                                                                                    <tr v-else>
                                                                                        <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td>{{$t('global.Total')}}</td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td> {{ item.debits }}</td>
                                                                                        <td> {{ item.creditor }}</td>

                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                        </div>
                                                                        <div role="tabpanel" :id="'tab-5-'+item.id" class="tab-pane fade">
                                                                            <loader v-if="loading"/>
                                                                            <div class="table-responsive">
                                                                                <div class="head-data-table">
                                                                                    <p>{{$t('global.Number')}} : {{item.id}}</p>
                                                                                    <p>{{$t('global.RegistrationDate')}} : {{item.date}}</p>
                                                                                </div>

                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ $t('global.ProcedureDate') }}</th>
                                                                                        <th>{{ $t('global.Procedure') }}</th>
                                                                                        <th>{{ $t('global.Employee') }}</th>
                                                                                        <th>{{ $t('global.AccountName') }}</th>
                                                                                        <th>{{ $t('global.Description') }}</th>
                                                                                        <th>{{ $t('global.Amount') }}</th>
                                                                                        <th>{{ $t('global.AccountType') }}</th>
                                                                                        <th>{{ $t('global.RegistrationDate') }}</th>

                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>

                                                                                    <tr v-for="(record,index) in item.restriction_record" v-if="item.restriction_record" :key="record.id">
                                                                                        <td>{{ index + 1 }}</td>
                                                                                        <td>{{ dateFormat(record.created_at) }}</td>
                                                                                        <td><span v-if="record.is_add ==1"> {{$t('global.Add')}}</span>  <span v-else> {{$t('global.Edit')}}</span></td>
                                                                                        <td>{{ record.user_name }}</td>
                                                                                        <td>{{ record.account_name }}</td>
                                                                                        <td>{{ record.description }}</td>
                                                                                        <td>{{ record.amount }}</td>
                                                                                        <td><span v-if="record.debit ==1"> {{$t('global.Debit')}}</span>  <span v-else> {{$t('global.Creditor')}}</span></td>
                                                                                        <td>{{ record.date }}</td>
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
            <Pagination :limit="2" :data="dailyPaginate" @pagination-change-page="getIncome">
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
        let dailies = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let treasury_id = ref('');
        let loading = ref(false);
        let totalDebit = ref(0);
        let totalCreditor = ref(0);
        const search = ref('');
        let dailyPaginate = ref({});

        let getIncome = (page = 1) => {
            loading.value = true;
            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }
            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

            adminApi.get(`/v1/dashboard/dailyRestriction?page=${page}&id=${treasury_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}&search=${search.value}`)
                .then((res) => {

                    let l = res.data.data;
                    dailyPaginate.value = l.dailies;
                    dailies.value = l.dailies.data;
                    totalDebit.value = l.debit;
                    totalCreditor.value = l.creditor;
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

        emitter.on('get_lang', () => {
            getIncome(search.value);
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
            var printContents = document.getElementById('printData-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {dailyPaginate,search,totalCreditor,permission,totalDebit,treasury_id,fromDate,toDate,dailies, loading,dateFormat,printData, getIncome,close};

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


</style>
