<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.AccountStatement') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.AccountStatement') }}</li>
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
                                        <form @submit.prevent="getByDate" class="needs-validation">
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

                                                <div class="col-md-5">

                                                    <label>{{$t('global.ChooseIncome')}} </label>

                                                    <select v-model="sub_account_id" class="form-select select-input" required>
                                                        <option v-for="account in accounts" :kay="account.id" :value="account.id">{{account.name}}</option>
                                                    </select>

                                                </div>

                                                <div class="col-md-1">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-12 row mt-3">
                                        <div class="col-6">
                                            {{ $t('global.Search') }}:
                                            <input type="search" v-model="search" class="custom"/>
                                        </div>
                                        <div class="col-6 d-flex justify-content-end">
                                            <button @click="printRestriction" class="btn btn-info print-button">
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
                                        <th>{{ $t('global.AccountingEntryNumber') }}</th>
                                        <th>{{ $t('global.Description') }}</th>
                                        <th>{{ $t('global.Debit') }}</th>
                                        <th>{{ $t('global.Creditor') }}</th>
                                        <th>{{ $t('global.RegistrationDate') }}</th>
                                        <th>{{ $t('global.Edit') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="restrictions.length">
                                        <tr v-for="(item,index) in restrictions"  :key="item.id">

                                            <td>{{ item.daily_restriction_id }}</td>
                                            <td>{{ item.description }}</td>
                                            <td v-if="item.debit == 1">{{ item.amount }}</td>
                                            <td v-else>0</td>
                                            <td v-if="item.debit == 0">{{ item.amount }}</td>
                                            <td v-else>0</td>
                                            <td>{{ item.daily_restriction.date }}</td>
                                            <td>
                                                <router-link v-if="permission.includes('DailyRestriction edit')"
                                                             :to="{name: 'editDailyRestriction', params: {lang: locale || 'ar',id:item.daily_restriction_id}}"
                                                             class="btn btn-sm btn-success me-2">
                                                    <i class="far fa-edit"></i> {{$t('global.Edit')}}
                                                </router-link>
                                            </td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr >
                                            <th class="text-center" colspan="6">{{ $t('global.NoDataFound') }}</th>
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
        let sub_account_id = ref('');
        let fromDate = ref('');
        let toDate = ref('');
        let loading = ref(false);
        const search = ref('');

        let getRestrictions = (page = 1) => {

            loading.value = true;

            adminApi.get(`/v1/dashboard/accountStatement?page=${page}&search=${search.value}&sub_account_id=${sub_account_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    restrictionsPaginate.value = l.restrictions;
                    restrictions.value = l.restrictions.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }
        let getAccounts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/accountDaily`)
                .then((res) => {
                    let l = res.data.data;
                    accounts.value= l.subAccount;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        let getByDate = (page = 1) => {

            loading.value = true;

            adminApi.get(`/v1/dashboard/accountStatement?page=${page}&sub_account_id=${sub_account_id.value}&from_date=${fromDate.value}&to_date=${toDate.value}`)
                .then((res) => {
                    let l = res.data.data;
                    restrictionsPaginate.value = l.restrictions;
                    restrictions.value = l.restrictions.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getRestrictions();
            getAccounts();
        });

        emitter.on('get_lang', () => {
            getRestrictions(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getRestrictions();
            }
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

        return {printRestriction,permission,fromDate,toDate,getByDate,restrictions,accounts,sub_account_id,loading, getRestrictions,dateFormat, search, restrictionsPaginate};

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
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.submit-margin{
    margin-top: 38px !important;
}
.date-input{
    width: 135px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}
.select-input{
    width: 235px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

</style>
