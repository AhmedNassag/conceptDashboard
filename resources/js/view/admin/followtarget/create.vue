<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.CommissionPlanManagement')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexfollowTargetPlan', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.CommissionPlanManagement') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexfollowTarget', params: {lang: locale || 'ar',id}}">
                                    {{ $t('global.Target') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('Target.CreateTarget') }}</li>

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
                                <router-link :to="{name: 'indexfollowTarget', params: {lang: locale || 'ar',id}}" class="btn btn-custom btn-dark">
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['from']">{{ errors['from'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['percent']">{{ errors['percent'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['to']">{{ errors['to'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['seller_category_id']">{{ errors['seller_category_id'][0] }}<br /> </div>

                                    <form @submit.prevent="storeTarget" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.From')}}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model="v$.from.$model"
                                                       id="validationCustom01"
                                                       :class="{'is-invalid':v$.from.$error,'is-valid':!v$.from.$invalid}"
                                                       :placeholder="$t('global.From')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.from.required.$invalid">{{$t('global.FromIsRequired')}} <br /></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{$t('global.To')}}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model="v$.to.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.to.$error,'is-valid':!v$.to.$invalid}"
                                                       :placeholder="$t('global.To')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.to.required.$invalid">{{$t('global.ToIsRequired')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3" v-if="id == 1 || id == 2 || id == 3 || id == 4">
                                                <label for="validationCustom03">{{$t('global.Amount')}}</label>
                                                <input type="number" step="0.1"
                                                       class="form-control"
                                                       v-model="v$.amount.$model"
                                                       id="validationCustom03"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                       :placeholder="$t('global.Amount')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{$t('global.AmountIsRequired')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3" v-if="id > 4">
                                                <label for="validationCustom04">{{$t('global.Percentage')}}</label>
                                                <input type="number" step="0.1"
                                                       class="form-control"
                                                       v-model="v$.percent.$model"
                                                       id="validationCustom04"
                                                       :class="{'is-invalid':v$.percent.$error,'is-valid':!v$.percent.$invalid}"
                                                       :placeholder="$t('global.Percentage')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.percent.required.$invalid">{{$t('global.PercentageIsRequired')}} <br /></span>
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
import {required,minLength,between,maxLength,alpha,integer} from '@vuelidate/validators';
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
    props:["id"],
    setup(props){
        const emitter = inject('emitter');
        const {t} = useI18n({});
        let loading = ref(false);
        const {id} = toRefs(props);

        let addAdvisor =  reactive({
            data:{
                from:'',
                to:'',
                amount:0,
                percent:0,
                seller_category_id :id
            }
        });

        const rules = computed(() => {
            return {
                from:{required},
                to:{required},
                amount:{required},
                percent:{required}
            }
        });

        const v$ = useVuelidate(rules,addAdvisor.data);

        return {t,loading,...toRefs(addAdvisor),v$,id};
    },
    methods: {
        storeTarget(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/followtarget`,this.data)
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
            this.data.from = '';
            this.data.to = '';
            this.data.amount = 0;
            this.data.percent = 0;
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
