<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.FinancialCenter') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.FinancialCenter') }} </li>
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
                        <div class="card-body" id="printExpense">
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getIncome" class="needs-validation">
                                            <div class="form-group row">

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
                            <div class="row" >
                                <div class="col-md-6">
                                    <div class="row m-0">
                                        <div class="col-12 head-table">
                                            <h3>{{$t('global.Assets')}}</h3>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive" >
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ $t('global.Statement') }}</th>
                                                        <th>{{ $t('global.Partial') }}</th>
                                                        <th>{{ $t('global.TotalAccount') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(item,index) in assets" v-if="assets" :key="item.id">

                                                        <td v-if="item.children.length > 0 && item.main_account_id == 1">{{ item.name }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.name}}</span> <br> {{ $t('global.Total') }}</td>
                                                        <td v-else>{{ item.name }} <span class="assets-spam" v-if="item.main_account_id == 2"> ({{$t('global.Opponents')}})</span></td>
                                                        <td v-if="item.children.length > 0 && item.main_account_id == 1">{{ item.balance }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.balance}}</span></td>
                                                        <td v-else></td>
                                                        <td v-if="item.children.length > 0 && item.main_account_id == 1"> <br> <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"> 0 <br></span> {{ item.totalBalances }}</td>
                                                        <td v-else>{{ item.totalBalances }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$t('global.Total') }}</td>
                                                        <td></td>
                                                        <td>{{ totalAsset }}</td>

                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="row m-0">
                                        <div class="col-12 head-table">
                                            <h3>{{$t('global.Opponents')}}</h3>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive" >
                                                <table class="table mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ $t('global.Statement') }}</th>
                                                        <th>{{ $t('global.Partial') }}</th>
                                                        <th>{{ $t('global.TotalAccount') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(item,index) in liabilities" v-if="liabilities" :key="item.id">
                                                        <td v-if="item.children.length > 0 && item.main_account_id == 2">{{ item.name }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.name}}</span> <br> {{ $t('global.Total') }}</td>
                                                        <td v-else>{{ item.name }}  <span class="assets-spam" v-if="item.main_account_id == 1"> ({{$t('global.Assets')}})</span></td>
                                                        <td v-if="item.children.length > 0 && item.main_account_id == 2">{{ item.creditBalances }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.creditBalances}}</span></td>
                                                        <td v-else></td>
                                                        <td v-if="item.children.length > 0 && item.main_account_id == 2"> <br> <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"> 0 <br></span> {{ item.totalBalances }}</td>
                                                        <td v-else>{{ item.totalBalances }}</td>
                                                    </tr>
                                                    <tr :class="[netProfit < 0 ? 'read_error': 'green-success']">
                                                        <td>{{$t('global.NetProfit') }}</td>
                                                        <td></td>
                                                        <td>{{ netProfit }}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$t('global.Total') }}</td>
                                                        <td></td>
                                                        <td>{{ totalLiabilities }}</td>

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
        let assets = ref([]);
        let liabilities = ref([]);
        let toDate = ref('');
        let loading = ref(false);
        let totalAsset = ref(0);
        let totalLiabilities = ref(0);
        let netProfit = ref(0);

        let getIncome = () => {

           loading.value = true;

            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

           adminApi.get(`/v1/dashboard/financialCenter?to_date=${toDate.value}`)
               .then((res) => {
                   let l = res.data.data;
                   totalAsset.value = 0;
                   totalLiabilities.value = 0;

                   //check and convert accounts

                   l.assets.forEach(el=>{
                       el.children.forEach(elChildren=>{
                           if (elChildren.sumDebit < elChildren.sumCredit){
                               l.liabilities.push(elChildren);
                           }
                       });
                   });

                   l.liabilities.forEach(el=>{
                       el.children.forEach(elChildren=>{
                           if (elChildren.sumDebit > elChildren.sumCredit){
                               l.assets.push(elChildren);
                           }
                       });
                   });

                   //--------------- end check and convert accounts ------------------

                   //start assets

                   assets.value = l.assets;
                   assets.value.forEach(el=>{
                       let debitBalances = 0;
                       let creditBalances = 0;
                       let totalBalances = 0;

                       if (el.sumDebit >= el.sumCredit){
                           debitBalances = el.sumDebit - el.sumCredit;
                       }else {
                           creditBalances = el.sumCredit - el.sumDebit;
                       }

                       if (el.children.length == 0){
                           el['balance'] =debitBalances;
                           totalAsset.value += debitBalances;
                           el['creditBalances'] =creditBalances;
                           totalBalances += debitBalances;
                       }

                       el.children.forEach(elChildren=>{
                           let debitChildBalances = 0;
                           let creditChildBalances = 0;
                           if (elChildren.sumDebit >= elChildren.sumCredit){
                               debitChildBalances =elChildren.sumDebit - elChildren.sumCredit;
                           }else {
                               creditChildBalances = elChildren.sumCredit - elChildren.sumDebit;
                               // l.liabilities.push(elChildren);
                           }

                           totalAsset.value += debitChildBalances;
                           elChildren['balance'] =debitChildBalances;
                           elChildren['creditBalances'] =creditChildBalances;

                           totalBalances += debitChildBalances;
                       });

                       el['totalBalances'] =totalBalances;

                   });

                    //-------------------- end assets -----------------------

                   //start liabilities

                   liabilities.value = l.liabilities;
                   liabilities.value.forEach(el=>{
                       let debitBalances = 0;
                       let creditBalances = 0;
                       let totalBalances = 0;

                       if (el.sumDebit >= el.sumCredit){
                           debitBalances = el.sumDebit - el.sumCredit;
                       }else {
                           creditBalances = el.sumCredit - el.sumDebit;
                       }

                       if (el.children.length == 0 || el.main_account_id == 1 ){

                           totalLiabilities.value += creditBalances;
                           el['creditBalances'] =creditBalances;
                           el['debitBalances'] =debitBalances;
                           totalBalances += creditBalances;

                       }
                       if (el.main_account_id == 2)
                       {
                           el.children.forEach(elChildren=>{

                               let debitChildBalances = 0;
                               let creditChildBalances = 0;

                               if (elChildren.sumDebit >= elChildren.sumCredit){
                                   debitChildBalances =elChildren.sumDebit - elChildren.sumCredit;
                               }else {
                                   creditChildBalances =elChildren.sumCredit - elChildren.sumDebit;
                               }
                               totalLiabilities.value += creditChildBalances;

                               elChildren['creditBalances'] =creditChildBalances;
                               elChildren['debitBalances'] =debitChildBalances;
                               totalBalances += creditChildBalances;
                           });
                       }

                       el['totalBalances'] = totalBalances;

                   });

                   //-------------------- end liabilities -----------------------

                   netProfit.value = totalAsset.value - totalLiabilities.value;
                   totalLiabilities.value += netProfit.value ;

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

        return {printExpense,toDate,assets,liabilities, loading, getIncome,dateFormat,totalAsset,totalLiabilities,netProfit};

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

@media print {
    .card-header{
        display: none;
    }
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
.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}

.read_error{
    background: red;
    color: #dee2e6;
}
.green-success{
    background: green;
    color: #dee2e6;
}

.assets-spam{
    color: red;
    font-weight:500;
}

</style>
