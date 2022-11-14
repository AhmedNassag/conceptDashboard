<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.DailyRestriction')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexDailyRestriction', params: {lang: locale || 'ar'}}">{{$t('global.DailyRestriction')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
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
                                    :to="{name: 'indexDailyRestriction', params: {lang: locale || 'ar'}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['date']">{{ errors['date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['desc']">{{ errors['desc'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['debit']">{{ errors['debit'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['creditors']">{{ errors['creditors'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.RegistrationDate')}}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.date.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.NameEn')"
                                                       :class="{'is-invalid':v$.date.$error,'is-valid':!v$.date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.date.required.$invalid">{{$t('global.NameEnIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Description')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.desc.$model" :class="['form-control text-height',{'is-invalid':v$.desc.$error,'is-valid':!v$.desc.$invalid}]" :placeholder="$t('global.Description')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.desc.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                    <span v-if="v$.desc.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.desc.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>


                                            <div class="col-md-12 mb-12">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Debit') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.debit" class="col-md-12 mb-12 body-account row">
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.AccountName') }}</label>

                                                            <select v-model="it.sub_account_id" :class="['form-select',{'is-invalid':v$.debit[index].sub_account_id.$error,'is-valid':!v$.debit[index].sub_account_id.$invalid}]">
                                                                <option v-for="debit in debits" :kay="debit.id" :value="debit.id">{{debit.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.debit[index].sub_account_id.required.$invalid">{{$t('global.DepartmentIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="validationCustom01">{{$t('global.RestrictionDescription')}}</label>
                                                            <textarea rows="4" cols="5" v-model.trim="v$.debit[index].description.$model" :class="['form-control text-height',{'is-invalid':v$.debit[index].description.$error,'is-valid':!v$.debit[index].description.$invalid}]" :placeholder="$t('global.RestrictionDescription')"></textarea>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.debit[index].description.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                                <span v-if="v$.debit[index].description.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.debit[index].description.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="validationCustom01">{{$t('global.Amount')}}</label>
                                                            <input type="number" class="form-control" @input="DebitAmount"
                                                                   v-model.number="v$.debit[index].amount.$model"
                                                                   :placeholder="$t('global.Amount')"
                                                                   :class="{'is-invalid':v$.debit[index].amount.$error,'is-valid':!v$.debit[index].amount.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.debit[index].amount.required.$invalid">{{$t('global.AmountIsRequired')}}<br /> </span>
                                                                <span v-if="v$.debit[index].amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addDebit" v-if="(data.debit.length-1) == index"
                                                                    class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteDebit(index)"
                                                                    data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-12 mb-12 mt-5 mb-5">
                                                <div class="row account">
                                                    <div class="col-md-12 mb-12 head-account">
                                                        <h3>{{ $t('global.Creditor') }}</h3>
                                                    </div>
                                                    <div v-for="(it,index) in data.creditors" class="col-md-12 mb-12 body-account row">
                                                        <div class="col-md-3 mb-3">
                                                            <label>{{ $t('global.AccountName') }}</label>

                                                            <select v-model="it.sub_account_id" :class="['form-select',{'is-invalid':v$.creditors[index].sub_account_id.$error,'is-valid':!v$.creditors[index].sub_account_id.$invalid}]">
                                                                <option v-for="item in creditor" :kay="item.id" :value="item.id">{{item.name}}</option>
                                                            </select>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.creditors[index].sub_account_id.required.$invalid">{{$t('global.DepartmentIsRequired')}}<br /> </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="validationCustom01">{{$t('global.RestrictionDescription')}}</label>
                                                            <textarea rows="4" cols="5" v-model.trim="v$.creditors[index].description.$model" :class="['form-control text-height',{'is-invalid':v$.creditors[index].description.$error,'is-valid':!v$.creditors[index].description.$invalid}]" :placeholder="$t('global.RestrictionDescription')"></textarea>
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.creditors[index].description.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                                <span v-if="v$.creditors[index].description.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.creditors[index].description.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label for="validationCustom01">{{$t('global.Amount')}}</label>
                                                            <input type="number" class="form-control" @input="CreditorAmount"
                                                                   v-model.number="v$.creditors[index].amount.$model"
                                                                   :placeholder="$t('global.Amount')"
                                                                   :class="{'is-invalid':v$.creditors[index].amount.$error,'is-valid':!v$.creditors[index].amount.$invalid}"
                                                            >
                                                            <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                            <div class="invalid-feedback">
                                                                <span v-if="v$.creditors[index].amount.required.$invalid">{{$t('global.AmountIsRequired')}}<br /> </span>
                                                                <span v-if="v$.creditors[index].amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <button @click.prevent="addCreditors" v-if="(data.creditors.length-1) == index"
                                                                    class="btn btn-sm btn-success me-2 mt-5">
                                                                <i class="fas fa-clipboard-list"></i> {{$t('global.AddANewLine')}}
                                                            </button>
                                                            <button v-if="index" @click.prevent="deleteCreditors(index)"
                                                                    data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2 mt-5">
                                                                <i class="far fa-trash-alt"></i> {{$t('global.Delete')}}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-center table-hover mb-0 datatable">
                                                        <thead class="account">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{ $t('global.Debit') }}</th>
                                                            <th>{{ $t('global.Creditor') }}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>{{$t('global.Total')}}</td>
                                                            <td>{{totalDebit}}</td>
                                                            <td>{{totalCreditor}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="error-amount">
                                                <p v-if="totalDebit != totalCreditor">{{$t('global.The total of the debit must be equal to the total of the creditor after posting all the accounting entries in all the previous operations')}}</p>
                                            </div>

                                        </div>


                                        <button :disabled="totalDebit != totalCreditor" class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
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
import {computed, onMounted, reactive,toRefs,inject,ref,watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, between, maxLength, alpha, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject('emitter');
        const {id} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let debits = ref([]);
        let creditor = ref([]);

        let debitValidation = ref([]);
        let CreditorsValidation = ref([]);

        let totalDebit = ref(0);
        let totalCreditor = ref(0);

        let DebitAmount = () => {
            totalDebit.value = 0;
            addJob.data.debit.forEach((el) => {
                totalDebit.value += parseFloat(el.amount);
            });
        }

        let CreditorAmount = () => {
            totalCreditor.value = 0;
            addJob.data.creditors.forEach((el) => {
                totalCreditor.value += parseFloat(el.amount);
            });
        }

        let getData = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/dailyRestriction/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    debits.value = l.subAccount;
                    creditor.value = l.subAccount;
                    addJob.data.desc = l.dailies.desc;
                    addJob.data.date = l.dailies.date;
                    l.dailies.restriction.forEach(el=>{
                        if(el.debit == 1) {
                            addJob.data.debit.push({
                                description:el.description,
                                amount:el.amount,
                                sub_account_id:el.sub_account_id,
                            });
                            debitValidation.value.push({
                                amount: {
                                    required,
                                    numeric

                                },
                                sub_account_id:{
                                    required
                                },
                                description:{
                                    required,
                                    minLength: minLength(3),
                                }
                            });
                            totalDebit.value += parseFloat(el.amount);
                        }else {
                            addJob.data.creditors.push({
                                description:el.description,
                                amount:el.amount,
                                sub_account_id:el.sub_account_id,
                            });
                            CreditorsValidation.value.push({
                                amount: {
                                    required,
                                    numeric

                                },
                                sub_account_id:{
                                    required
                                },
                                description:{
                                    required,
                                    minLength: minLength(3),
                                }
                            });
                            totalCreditor.value += parseFloat(el.amount);
                        }
                    });
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getData();
        });

        let addJob =  reactive({
            data:{
                debit:[],
                creditors:[],
                desc:'',
                date:new Date().toISOString().split('T')[0],
            }
        });

        const rules = computed(() => {
            return {
                debit:[
                    ...debitValidation.value
                ],
                creditors:[
                    ...CreditorsValidation.value
                ],

                desc:{
                    required,
                    minLength: minLength(3),
                },
                date:{
                    required
                }
            }
        });

        const v$ = useVuelidate(rules,addJob.data);

        return {t,debits,creditor,loading,...toRefs(addJob),v$,totalDebit,totalCreditor,debitValidation,DebitAmount,CreditorAmount,CreditorsValidation};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/dailyRestriction/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                    })
                    .catch((err) => {
                        console.log(err);
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        addDebit(){
            this.data.debit.push({description:'', amount:null, sub_account_id:''});
            this.debitValidation.push({
                amount: {
                    required,
                    numeric

                },
                sub_account_id:{
                    required
                },
                description:{
                    required,
                    minLength: minLength(3),
                }
            });

            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteDebit(index){
            this.data.debit.splice(index,1);
            this.debitValidation.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.DebitAmount();
        },
        addCreditors(){
            this.data.creditors.push({description:'', amount:null, sub_account_id:''});
            this.CreditorsValidation.push({
                amount: {
                    required,
                    numeric
                },
                sub_account_id:{
                    required
                },
                description:{
                    required,
                    minLength: minLength(3),
                }
            });

            this.$nextTick(() => { this.v$.$reset() });
        },
        deleteCreditors(index){
            this.data.creditors.splice(index,1);
            this.CreditorsValidation.splice(index,1);
            this.$nextTick(() => { this.v$.$reset() });
            this.CreditorAmount();
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
.account{
    background-color: #fcb00c;
    color: #000000 !important;
    border-radius: 5px;
}
.head-account{
    display: flex;
    justify-content: center;
}
.head-account h3{
    color: #000000 !important;
    font-weight: bold;
}
.body-account{
    border-top: 3px solid #000000;
    margin: 0 !important;
}
.text-height{
    height: 46px !important;
}
.error-amount{
    display: flex;
    justify-content: center;
    color: red;
    margin: 10px;
}
</style>
