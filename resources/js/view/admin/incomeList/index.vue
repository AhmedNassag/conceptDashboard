<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.IncomeList') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.IncomeList') }} </li>
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
                            <div class="row" >
                            <div class="col-md-12">
                                <div class="row m-0">
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
                                                <tr class="head-table">
                                                    <td>{{$t('global.Incomes') }}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                <tr v-for="(item,index) in liabilities" v-if="liabilities" :key="item.id">
                                                    <td v-if="item.children.length > 0">{{ item.name }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.name}}</span> <br> {{ $t('global.Total') }}</td>
                                                    <td v-else>{{ item.name }}</td>
                                                    <td v-if="item.children.length > 0">{{ item.balance }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.balance}}</span></td>
                                                    <td v-else></td>
                                                    <td v-if="item.children.length > 0"> <br> <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"> 0 <br></span> {{ item.totalBalances }}</td>
                                                    <td v-else>{{ item.totalBalances }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{$t('global.TotalIncome') }}</td>
                                                    <td></td>
                                                    <td>{{ totalLiabilities }}</td>

                                                </tr>

                                                <tr class="head-table">
                                                    <td>{{$t('global.Expenses') }}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                <tr v-for="(item,index) in assets" v-if="assets" :key="item.id">

                                                    <td v-if="item.children.length > 0 && item.id != 39">{{ item.name }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.name}}</span> <br> {{ $t('global.Total') }}</td>
                                                    <td v-else v-if="item.id != 39">{{ item.name }}</td>
                                                    <td v-if="item.children.length > 0 && item.id != 39">{{ item.balance }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.balance}}</span></td>
                                                    <td v-else v-if="item.id != 39"></td>
                                                    <td v-if="item.children.length > 0 && item.id != 39"> <br> <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"> 0 <br></span> {{ item.totalBalances }}</td>
                                                    <td v-else v-if="item.id != 39">{{ item.totalBalances }}</td>
                                                </tr>

                                                <tr>
                                                    <td>{{$t('global.TotalExpense') }}</td>
                                                    <td></td>
                                                    <td>{{ totalAsset }}</td>

                                                </tr>
                                                <tr :class="[netProfit < 0 ? 'read_error': 'green-success']">
                                                    <td>{{$t('global.NetProfitBeforeTax') }}</td>
                                                    <td></td>
                                                    <td>{{ netProfit }}</td>

                                                </tr>

                                                <tr v-for="(item,index) in assets" v-if="assets" :key="item.id">

                                                    <td v-if="item.children.length > 0 && item.id == 39">{{ item.name }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.name}}</span> <br> {{ $t('global.Total') }}</td>
                                                    <td v-else v-if="item.id == 39">{{ item.name }}</td>
                                                    <td v-if="item.children.length > 0 && item.id == 39">{{ item.balance }} <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"><br> {{child.balance}}</span></td>
                                                    <td v-else v-if="item.id == 39"></td>
                                                    <td v-if="item.children.length > 0 && item.id == 39"> <br> <span v-for="(child,index) in item.children" v-if="item.children" :key="child.id"> 0 <br></span> {{ item.totalBalances }}</td>
                                                    <td v-else v-if="item.id == 39">{{ item.totalBalances }}</td>
                                                </tr>

                                                <tr :class="[netProfit - tax < 0 ? 'read_error': 'green-success']">
                                                    <td>{{$t('global.NetProfit') }}</td>
                                                    <td></td>
                                                    <td>{{ netProfit - tax }}</td>

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
        let fromDate = ref('');
        let loading = ref(false);
        let totalAsset = ref(0);
        let totalLiabilities = ref(0);
        let netProfit = ref(0);
        let tax = ref(0);

        let getIncome = () => {

           loading.value = true;

            if (!fromDate.value){
                fromDate.value = new Date().toISOString().split('T')[0];
            }
            if (!toDate.value){
                toDate.value = new Date().toISOString().split('T')[0];
            }

           adminApi.get(`/v1/dashboard/incomeList?from_date=${fromDate.value}&to_date=${toDate.value}`)
               .then((res) => {
                   let l = res.data.data;
                   totalAsset.value = 0;
                   totalLiabilities.value = 0;
                   tax.value = 0;
                   assets.value = l.expenses;
                   assets.value.forEach(el=>{
                       let debitBalances = 0;
                       let creditBalances = 0;
                       let totalBalances = 0;

                       if (el.sumDebit >= el.sumCredit){
                           debitBalances =el.sumDebit - el.sumCredit;
                       }else {
                           creditBalances =el.sumCredit - el.sumDebit;
                       }

                       if (el.children.length == 0){
                           el['balance'] =debitBalances;
                           if (el.id !== 39){
                               totalAsset.value += el.sumDebit - el.sumCredit;
                           }else {
                               tax.value += el.sumDebit - el.sumCredit;
                           }

                           el['creditBalances'] =creditBalances;
                           totalBalances += debitBalances;
                       }

                       el.children.forEach(elChildren=>{
                           let debitChildBalances = 0;
                           let creditChildBalances = 0;
                           if (elChildren.sumDebit >= elChildren.sumCredit){
                               debitChildBalances =elChildren.sumDebit - elChildren.sumCredit;
                           }else {
                               creditChildBalances =elChildren.sumCredit - elChildren.sumDebit;
                           }
                           if (el.id !== 39){
                               totalAsset.value += elChildren.sumDebit - elChildren.sumCredit;
                           }else {
                               tax.value += elChildren.sumDebit - elChildren.sumCredit;
                           }

                           elChildren['balance'] =debitChildBalances;
                           elChildren['creditBalances'] =creditChildBalances;

                           totalBalances += debitChildBalances;
                       });

                       el['totalBalances'] =totalBalances;

                   });

                   liabilities.value = l.incomes;
                   liabilities.value.forEach(el=>{
                       let debitBalances = 0;
                       let creditBalances = 0;
                       let totalBalances = 0;

                       if (el.sumDebit >= el.sumCredit){
                           debitBalances =el.sumDebit - el.sumCredit;
                       }else {
                           creditBalances =el.sumCredit - el.sumDebit;
                       }
                       if (el.children.length == 0){

                           totalLiabilities.value += el.sumCredit - el.sumDebit;
                           el['balance'] =creditBalances;
                           el['debitBalances'] =debitBalances;
                           totalBalances += creditBalances;

                       }
                       el.children.forEach(elChildren=>{

                           let debitChildBalances = 0;
                           let creditChildBalances = 0;

                           if (elChildren.sumDebit >= elChildren.sumCredit){
                               debitChildBalances =elChildren.sumDebit - elChildren.sumCredit;
                           }else {
                               creditChildBalances =elChildren.sumCredit - elChildren.sumDebit;
                           }
                           totalLiabilities.value += elChildren.sumCredit - elChildren.sumDebit;

                           elChildren['balance'] =creditChildBalances;
                           elChildren['debitBalances'] =debitChildBalances;
                           totalBalances += creditChildBalances;
                       });
                       el['totalBalances'] =totalBalances;

                   });
                   netProfit.value = totalLiabilities.value - totalAsset.value;

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

        return {printExpense,tax,toDate,fromDate,assets,liabilities, loading, getIncome,dateFormat,totalAsset,totalLiabilities,netProfit};

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

</style>
