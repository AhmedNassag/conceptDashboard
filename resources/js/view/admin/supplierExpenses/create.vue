<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.supplierExpenses')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSupplierExpenses'}">{{$t('global.supplierExpenses')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Add')}}</li>
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
                                    :to="{name: 'indexSupplierExpenses'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['supplier_id']">{{ errors['supplier_id'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes']">{{ errors['notes'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['purchase_id']">{{ errors['purchase_id'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['payment_date']">{{ errors['payment_date'][0] }}<br/> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3 position-relative">
                                                <label> {{ $t('global.ChooseSupplier') }} </label>
                                                <label class="balance"> {{$t('global.Balance')}} : {{balance}}</label>

                                                <input type="text"
                                                   class="form-control mb-1 input-Sender"
                                                    v-model="search"
                                                    @input="searchSender"
                                                    @blur="isButton = true"
                                                    @focus="searchSender"
                                                    autofocus
                                                   :placeholder="$t('treasury.Search')"
                                                >
                                                <ul class="dropdown-search sender-search" v-if="dropDownSenders.length">
                                                    <li
                                                        class="Sender"
                                                        v-for="(dropDownSender,index) in dropDownSenders"
                                                        :key="index"
                                                        @click="showSenderName(index)"
                                                        @mouseenter="senderHover"
                                                    >
                                                        {{ dropDownSender.name_supplier }}
                                                    </li>
                                                </ul>

                                                <input type="text"
                                                       :class="['form-control',{'is-invalid':v$.supplier_id.$error,'is-valid':!v$.supplier_id.$invalid}]"
                                                       disabled
                                                       v-model="nameSender"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.supplier_id.required.$invalid">{{$t('global.supplierIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom10">{{ $t('global.Amount') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.amount.$model"
                                                       id="validationCustom10"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                       :placeholder="$t('global.Amount')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{ $t('global.AmountIsRequired') }} <br/></span>
                                                    <span v-if="v$.amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label >{{ $t('global.Date_Pay') }}</label>
                                                <input type="date"
                                                       class="form-control"
                                                       v-model="v$.payment_date.$model"
                                                       :class="{'is-invalid':v$.payment_date.$error,'is-valid':!v$.payment_date.$invalid}"
                                                       :placeholder="$t('global.Date_Pay')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.payment_date.required.$invalid">{{ $t('global.ThisFieldIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Balance')}} : {{parseFloat(Math.round(treasury_amount))}}</span> </label>
                                                <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('expense.ChooseExpense')}} </label>
                                                <select v-model="data.expense_id" class="form-select" :class="{'is-invalid':v$.expense_id.$error,'is-valid':!v$.expense_id.$invalid}">
                                                    <option v-for="expense in expenses" :kay="expense.id" :value="expense.id">{{expense.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.expense_id.required.$invalid">{{$t('global.ExpenseIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.PurchaseInvoiceNumber') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.number="v$.purchase_id.$model"
                                                       :class="{'is-invalid':v$.purchase_id.$error,'is-valid':!v$.purchase_id.$invalid}"
                                                       :placeholder="$t('global.PurchaseInvoiceNumber')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.notes.$model" :class="['form-control text-height',{'is-invalid':v$.notes.$error,'is-valid':!v$.notes.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.notes.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.notes.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn btn-primary mt-2" type="submit" v-if="isButton">{{$t('global.Submit')}}</button>
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
    name: "create",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        const {t} = useI18n({});
        let loading = ref(false);
        let suppliers = ref([]);
        let treasuries = ref([]);
        let expenses = ref([]);
        let treasury_amount = ref(0);
        let isButton = ref(true);
        let dropDownSenders = ref([ ]);

        let getData = () => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/supplierExpense/create`)
                .then((res) => {
                    let l = res.data.data;
                    suppliers.value = l.suppliers;
                    expenses.value = l.expenses;
                    treasuries.value = l.treasuries;
                    treasury_amount.value = l.treasuries[0].amount;
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
                notes:'',
                supplier_id:'',
                expense_id:'',
                treasury_id:1,
                amount:'',
                purchase_id:'',
                payment_date:'',
            },
            nameSender: '',
            search: '',
            balance:0
        });

        const rules = computed(() => {
            return {
                notes:{
                    minLength: minLength(5),
                },
                supplier_id:{
                    required
                },
                treasury_id:{
                    required
                },
                purchase_id:{},
                expense_id:{
                    required
                },
                payment_date:{
                    required
                },
                amount:{
                    required,
                    numeric
                }
            }
        });

        let searchSender = () => {
            dropDownSenders.value = [];
            if(addJob.search){
                let thisString = new RegExp(addJob.search,'i');
                let items = suppliers.value.filter(e => e.name_supplier.match(thisString) || e.id == addJob.search);
                dropDownSenders.value = items.splice(0,10);
            }else{
                dropDownSenders.value = [];
            }

            isButton.value = false;
        };

        let showSenderName = (index) => {
            let item = dropDownSenders.value[index];
            addJob.nameSender = item.name_supplier ;
            addJob.data.supplier_id = item.id;
            addJob.balance = item.sum_account;
            addJob.search = '';
            dropDownSenders.value = [];
        };

        let senderHover = (e) => {
            let items = document.querySelectorAll('.sender-search .Sender');
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

        document.addEventListener('keyup',(e) => {

            if(e.keyCode == 38){ //top arrow
                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    let isTrue = false;
                    let index = null;
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) {
                            isTrue = true;
                            index = i;
                        }
                    });
                    if(isTrue && index != 0){
                        items[index].classList.remove('active');
                        items[index - 1].classList.add('active');
                    }else if(isTrue && index == 0){
                        items[index].classList.remove('active');
                        items[items.length - 1].classList.add('active');
                    }
                    if(!isTrue) items[0].classList.add('active');
                }else {
                    dropDownSenders.value = [];
                }
            };

            if(e.keyCode == 40){ //down arrow
                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    let isTrue = false;
                    let index = null;
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) {
                            isTrue = true;
                            index = i;
                        }
                    });
                    if(isTrue && index != (items.length - 1)){
                        items[index].classList.remove('active');
                        items[index + 1].classList.add('active');
                    }else if(isTrue && index == (items.length - 1)){
                        items[index].classList.remove('active');
                        items[0].classList.add('active');
                    }
                    if(!isTrue) items[items.length - 1].classList.add('active');
                }else {
                    dropDownSenders.value = [];
                }
            };

            if(e.keyCode == 13){ //enter

                if(dropDownSenders.value.length > 0){
                    let items = document.querySelectorAll('.sender-search .Sender');
                    items.forEach((e,i) => {
                        if(e.classList.contains('active')) showSenderName(i);
                    });
                }else {
                    dropDownSenders.value = [];
                }
                e.target.blur();
            };

        });

        document.addEventListener('click',(e) => {
            if(dropDownSenders.value.length > 0){
                if(!e.target.classList.contains('Sender') && !e.target.classList.contains('input-Sender') && e.pointerType){
                    dropDownSenders.value = [];
                }
            }
        });

        const v$ = useVuelidate(rules,addJob.data);

        watch(()=>addJob.data.treasury_id,(after,before) =>{
            let v = treasuries.value.filter((el)=> el.id == addJob.data.treasury_id);
            treasury_amount.value = v[0].amount;
        });

        return {
            t,
            isButton,
            showSenderName,
            searchSender,
            treasuries,
            expenses,
            treasury_amount,
            dropDownSenders,
            suppliers,
            loading,
            getData,
            ...toRefs(addJob),
            senderHover,
            v$,
        };
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/supplierExpense`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.getData();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        // console.log(err.response)
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },resetForm(){
            this.data.notes = '';
            this.data.supplier_id = '';
            this.data.treasury_id = 1;
            this.data.expense_id = '';
            this.data.amount = '';
            this.data.purchase_id = '';
            this.data.payment_date = '';
            this.balance = 0;
            this.search = '';
            this.nameSender = '';
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
.head-account h3{
    color: #000000 !important;
    font-weight: bold;
}

.text-height{
    height: 46px !important;
}
.dropdown-search{
    position: absolute;
    width: 97%;
    background-color: #fff;
    border: 1px solid #d9d3d3;
    top: 83px;
    z-index: 10;
    padding: 0;
}

.dropdown-search li{
    padding: 5px;
}

.dropdown-search li:not(:last-child){
    border-bottom: 1px solid #d9d3d3;
}

.dropdown-search li:hover{
    background-color: #f1f1f1;
    cursor: pointer;
}

.dropdown-search li.active{
    background-color: #f1f1f1;
    cursor: pointer;
}
.balance{
    padding: 0 25px 0 0;
}
</style>
