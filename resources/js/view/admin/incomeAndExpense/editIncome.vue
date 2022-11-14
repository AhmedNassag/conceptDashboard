<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Incomes')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexIncomeAndExpense', params: {lang: locale || 'ar'}}">{{$t('global.IncomeAndExpense')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('income.EditIncomingAccounts')}}</li>
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
                                    <div class="alert alert-danger text-center" v-if="errors['income_id']">{{ errors['income_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes']">{{ errors['notes'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['payer']">{{ errors['payer'][0] }}<br /> </div>
                                    <form @submit.prevent="editIncome" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('income.ChooseIncome')}}</label>
                                                <select v-model="data.income_id" class="form-select" :class="{'is-invalid':v$.income_id.$error,'is-valid':!v$.income_id.$invalid}">
                                                    <option v-for="income in mainIncome" :kay="income.id" :value="income.id">{{income.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.income_id.required.$invalid">{{$t('global.IncomeIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Amount')}} : {{parseFloat(Math.round(amount))}}</span> </label>
                                                <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
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
                                                <label for="validationCustom01">{{$t('global.NameOfThePayer')}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.payer.$model"
                                                       id="validationCustom09"
                                                       :placeholder="$t('global.NameOfThePayer')"
                                                       :class="{'is-invalid':v$.payer.$error,'is-valid':!v$.payer.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.payer.required.$invalid">{{$t('global.NameOfThePayerIsRequired')}}<br /> </span>
                                                    <span v-if="v$.payer.minLength.$invalid">{{$t('global.NameOfThePayerIsMustHaveAtLeast')}} {{ v$.payer.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
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
import {computed, onMounted, reactive, toRefs, inject, ref, watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editIncome",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject('emitter');

        const {id} = toRefs(props)
        const {t} = useI18n({});
        // get create Package
        let mainIncome = ref([]);
        let income = ref({});
        let loading = ref(false);
        let treasuries = ref([]);
        let amount = ref(0);


        let getMainIncomeViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/incomeAndExpense/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    mainIncome.value= l.mainIncome;
                    addIncome.data.amount = l.income.amount;
                    addIncome.data.notes = l.income.notes;
                    addIncome.data.payment_date = l.income.payment_date;
                    addIncome.data.income_id = l.income.income_id;
                    addIncome.data.treasury_id = l.income.treasury_id;
                    addIncome.data.payer = l.income.payer;
                    treasuries.value=l.treasuries;
                    let val = treasuries.value.filter((el)=> el.id == addIncome.data.treasury_id);
                    amount.value = val[0].amount;

                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainIncomeViews();
        });


        //start design
        let addIncome =  reactive({
            data:{
                amount:null,
                income_id: null,
                treasury_id: null,
                payer: null,
                notes:null,
                payment_date: null
            }
        });

        const rules = computed(() => {
            return {
                amount: {
                    required,
                    numeric

                },
                income_id:{
                    required
                },
                treasury_id:{
                    required
                },
                notes:{
                    required,
                    minLength: minLength(3),
                },
                payer:{
                    required,
                    minLength: minLength(3),
                },
                payment_date:{
                    required,
                }
            }
        });
        watch(()=>addIncome.data.treasury_id,(newValue,old)=>{
            if (old){
                let val = treasuries.value.filter((el)=> el.id == addIncome.data.treasury_id);
                amount.value = val[0].amount;
            }
        });


        const v$ = useVuelidate(rules,addIncome.data);


        return {t,amount,treasuries,mainIncome,loading,...toRefs(addIncome),v$,getMainIncomeViews};
    },
    methods: {
        editIncome(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/incomeAndExpense/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.getMainIncomeViews();

                    })
                    .catch((err) => {
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
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
