<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Expenses')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexIncomeAndExpense', params: {lang: locale || 'ar'}}">{{$t('global.IncomeAndExpense')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('expense.AddExpensingAccounts')}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading" />
                        <div class="card-body">
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexIncomeAndExpense', params: {lang: locale || 'ar'}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['payment_date']">{{ errors['payment_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['expense_id']">{{ errors['expense_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes']">{{ errors['notes'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['payer']">{{ errors['payer'][0] }}<br /> </div>
                                    <form @submit.prevent="storeExpense" class="needs-validation">
                                        <div class="form-group row">
                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('expense.ChooseExpense')}}</label>

                                                <select v-model="data.expense_id" class="form-select" :class="{'is-invalid':v$.expense_id.$error,'is-valid':!v$.expense_id.$invalid}">
                                                    <option v-for="expense in mainExpense" :kay="expense.id" :value="expense.id">{{expense.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.expense_id.required.$invalid">{{$t('global.ExpenseIsRequired')}}<br /> </span>
                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Amount')}} : {{parseFloat(Math.round(amount))}}</span> </label>
                                                <select @change="amountTreasury" v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Amount')}}</label>
                                                <input type="number" class="form-control" step=".01"
                                                       v-model.trim="v$.amount.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Amount')"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{$t('global.AmountIsRequired')}}<br /> </span>
                                                    <span v-if="v$.amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.RecipientsName')}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.payer.$model"
                                                       id="validationCustom09"
                                                       :placeholder="$t('global.RecipientsName')"
                                                       :class="{'is-invalid':v$.payer.$error,'is-valid':!v$.payer.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.payer.required.$invalid">{{$t('global.RecipientsNameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.payer.minLength.$invalid">{{$t('global.RecipientsNameIsMustHaveAtLeast')}} {{ v$.payer.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.For')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.notes.$model" :class="['form-control',{'is-invalid':v$.notes.$error,'is-valid':!v$.notes.$invalid}]" :placeholder="$t('global.For')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.notes.required.$invalid">{{$t('global.NotesIsRequired')}}<br /> </span>
                                                    <span v-if="v$.notes.minLength.$invalid">{{$t('global.NotesIsMustHaveAtLeast')}} {{ v$.notes.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.PaymentDate')}}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.payment_date.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.payment_date.$error,'is-valid':!v$.payment_date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.payment_date.required.$invalid">{{$t('global.PaymentDateIsRequired')}}<br /> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
        </div>
    </div>
</template>

<script>
import {computed, onMounted, reactive,toRefs,inject,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required,minLength,numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "createExpense",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        const emitter = inject('emitter');
        const {t} = useI18n({});
        let mainExpense = ref([]);
        let loading = ref(false);
        let treasuries = ref([]);
        let amount = ref(0);

        let getTreasuries = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeTreasury`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l);
                    treasuries.value= l.treasuries;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        let getMainExpenseViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeExpense`)
                .then((res) => {
                    let l = res.data.data;
                    mainExpense.value= l.expenses;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

         onMounted(() => {
             getMainExpenseViews();
             getTreasuries();
        });

        emitter.on('get_lang', () => {
            getMainExpenseViews();
            getTreasuries();
        });

        //start design
        let addExpense =  reactive({
            data:{
                amount:null,
                notes:null,
                payment_date: null,
                treasury_id: null,
                payer:null,
                expense_id: null
            }
        });

        let amountTreasury =()=>{
            let v = treasuries.value.filter((el)=> el.id == addExpense.data.treasury_id);
            amount.value = v[0].amount;
        }

        const rules = computed(() => {
            return {
                amount: {
                    required,
                    numeric

                },
                treasury_id:{
                    required
                },
                payer:{
                    required,
                    minLength: minLength(3),
                },
                expense_id:{
                    required
                },
                notes:{
                    required,
                    minLength: minLength(3),
                },
                payment_date:{
                    required,
                }

            }
        });


        const v$ = useVuelidate(rules,addExpense.data);


        return {amountTreasury,amount,treasuries,t,mainExpense,loading,...toRefs(addExpense),v$};
    },
    methods: {
        storeExpense(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/incomeAndExpense`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.notes = '';
            this.data.payment_date = '';
            this.data.amount = '';
            this.data.expense_id = '';
            this.data.treasury_id = '';
            this.data.payer = '';
        }
    }
}
</script>

<style scoped>
.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}
.amount{
    background-color: #fcb00c;
    color: #000;
    margin: 50px;
}
</style>
