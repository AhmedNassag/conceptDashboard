<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.EarnedDiscount')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexEarnedDiscount'}">{{$t('global.EarnedDiscount')}}</router-link></li>
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
                                    :to="{name: 'indexEarnedDiscount'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['company_id']">{{ errors['company_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['purchase_id']">{{ errors['purchase_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['note']">{{ errors['note'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['discount']">{{ errors['discount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['to_date']">{{ errors['to_date'][0] }}<br /> </div>

                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChooseCompany') }}</label>

                                                <select v-model="data.company_id" :class="['form-select',{'is-invalid':v$.company_id.$error,'is-valid':!v$.company_id.$invalid}]">
                                                    <option v-for="company in companies" :kay="company.id" :value="company.id">{{company.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.company_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.discountValue') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.discount.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.discount.$error,'is-valid':!v$.discount.$invalid}"
                                                       :placeholder="$t('global.discountValue')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.discount.required.$invalid">{{ $t('global.discountValueIsRequired') }} <br/></span>
                                                    <span v-if="v$.discount.numeric.$invalid">{{$t('global.discountValueIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ToDate') }}</label>
                                                <input type="date"
                                                       class="form-control"
                                                       v-model="v$.to_date.$model"
                                                       :class="{'is-invalid':v$.to_date.$error,'is-valid':!v$.to_date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.to_date.required.$invalid">{{ $t('global.ThisFieldIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.PurchaseInvoiceNumber') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control is-valid"
                                                       v-model.number="data.purchase_id"
                                                       :placeholder="$t('global.PurchaseInvoiceNumber')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.note.$model" :class="['form-control text-height',{'is-invalid':v$.note.$error,'is-valid':!v$.note.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.note.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                    <span v-if="v$.note.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.note.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
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
        let companies = ref([]);

        let getCompanies = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/earnedDiscount/create`)
                .then((res) => {
                    let l = res.data.data;
                    companies.value= l.companies;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getCompanies();
        });

        let addJob =  reactive({
            data:{
                company_id : '',
                discount:'',
                to_date:'',
                note:'',
                purchase_id:'',
            }
        });

        const rules = computed(() => {
            return {
                company_id: {required},
                to_date: {required},
                discount:{
                    required,
                    numeric
                },
                note:{
                    required,
                    minLength: minLength(3),
                },
            }
        });


        const v$ = useVuelidate(rules,addJob.data);


        return {t,companies,loading,...toRefs(addJob),v$};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/earnedDiscount`,this.data)
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
            this.data.company_id = '';
            this.data.to_date = '';
            this.data.discount = '';
            this.data.note = '';
            this.data.purchase_id = '';
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
</style>
