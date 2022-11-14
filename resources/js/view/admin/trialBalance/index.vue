<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.TrialBalance') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.TrialBalance') }} </li>
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
                                                    <input type="date" class="form-control date-input"
                                                           v-model="fromDate">
                                                </div>

                                                <div class="col-md-4">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input" v-model="toDate">
                                                </div>


                                                <div class="col-md-2">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <button @click="printExpense" class="btn btn-success print-button">
                                                        {{$t('global.Print')}}
                                                        <i class="fa fa-print"></i>
                                                    </button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" id="printExpense">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{ $t('global.AccountName') }}</th>
                                        <th class="text-center">{{ $t('global.Totals') }}</th>
                                        <th></th>
                                        <th class="text-center">{{ $t('global.Holding') }}</th>

                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Debit') }}</th>
                                        <th>{{ $t('global.Creditor') }}</th>
                                        <th>{{ $t('global.Debit') }}</th>
                                        <th>{{ $t('global.Creditor') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,index) in subAccounts" v-if="subAccounts" :key="item.id">
                                        <td>{{item.name}}</td>
                                        <td>{{item.sumDebit}}</td>
                                        <td>{{item.sumCredit}}</td>
                                        <td>{{item.debitBalances}}</td>
                                        <td>{{item.creditBalances}}</td>
                                    </tr>

                                    <tr>
                                        <td>{{$t('global.Total')}}</td>
                                        <td>{{$t('global.TotalsDifference')}}</td>
                                        <td>{{totalsDifference}}</td>
                                        <td>{{$t('global.BalanceDifference')}}</td>
                                        <td>{{balanceDifference}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
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
        let subAccounts = ref([]);
        let fromDate = ref('');
        let toDate = ref('');
        let loading = ref(false);
        let totalsDifference = ref(0);
        let balanceDifference = ref(0);

        let getIncome = () => {

           loading.value = true;
            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }

            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

           adminApi.get(`/v1/dashboard/trialBalance?from_date=${fromDate.value}&to_date=${toDate.value}`)
               .then((res) => {
                   let l = res.data.data;

                   subAccounts.value = l.subAccount;
                   subAccounts.value.forEach(el=>{
                       let sumDebit = 0;
                       let sumCredit = 0;
                       let debitBalances = 0;
                       let creditBalances = 0;
                       el.restriction.forEach(elm=>{
                           if (elm.debit == 1){
                               sumDebit += parseFloat(elm.amount);
                           }else {
                               sumCredit += parseFloat(elm.amount);
                           }
                       });
                       if (sumDebit >= sumCredit){
                           debitBalances =sumDebit - sumCredit;
                       }else {
                           creditBalances =sumCredit - sumDebit;
                       }
                       totalsDifference.value += sumDebit - sumCredit;
                       balanceDifference.value += debitBalances - creditBalances;
                       el['sumDebit'] =sumDebit;
                       el['sumCredit'] =sumCredit;
                       el['debitBalances'] =debitBalances;
                       el['creditBalances'] =creditBalances;
                   });
                   console.log(subAccounts.value);
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
            getIncome();
        });

        let printExpense = () => {
            var printContents = document.getElementById('printExpense').innerHTML;
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

        return {printExpense,fromDate,toDate,subAccounts, loading, getIncome,dateFormat,totalsDifference,balanceDifference};

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
    width: 220px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

</style>
