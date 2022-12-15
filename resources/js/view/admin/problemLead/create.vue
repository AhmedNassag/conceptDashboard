<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Leads')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexProblemLeadSalesHome', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Categories') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexProblemLead', params: {lang: locale || 'ar',id}}">
                                    {{$t('global.Leads')}}
                                </router-link>
                            </li>
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
                                    :to="{name: 'indexProblemLead', params: {lang: locale || 'ar',id}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">{{ errors['address'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['description']">{{ errors['description'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['seller_category_id']">{{ errors['seller_category_id'][0] }}<br /> </div>

                                    <form @submit.prevent="storeLead" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Name')}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Name')"
                                                       :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.name.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.name.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.name.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.name.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{$t('global.Address')}}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                       :placeholder="$t('global.Address')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.minLength.$invalid">{{$t('global.AddressIsMustHaveAtLeast')}} {{ v$.address.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.address.maxLength.$invalid">{{$t('global.AddressIsMustHaveAtMost')}} {{ v$.address.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.Phone') }}</label>
                                                <input type="text"
                                                    class="form-control"
                                                    v-model.trim="v$.phone.$model"
                                                    id="validationCustom03"
                                                    :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                    :placeholder="$t('global.Phone')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span v-if="v$.phone.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{v$.phone.minLength.$params.min }} {{ $t('global.Letters') }} <br/></span>
                                                    <span v-if="v$.phone.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{v$.phone.maxLength.$params.max }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">{{ $t('global.Description') }}</label>
                                                <textarea type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.description.$model"
                                                       id="validationCustom04"
                                                       :class="{'is-invalid':v$.email.$error,'is-valid':!v$.description.$invalid}"
                                                       :placeholder="$t('global.Description')"
                                                ></textarea>
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.description.required.$invalid">{{ $t('global.NameIsRequired') }}<br/></span>
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
import {required, minLength, between, maxLength, alpha, integer, email} from '@vuelidate/validators';
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

        let addIncome =  reactive({
            data: {
                name: '',
                address: '',
                phone: '',
                description: '',
                seller_category_id: id
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                address: {
                    minLength: minLength(3),
                    maxLength:maxLength(150),
                },
                phone: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                description: {
                    required
                },
            }
        });


        const v$ = useVuelidate(rules,addIncome.data);


        return {t,id,loading,...toRefs(addIncome),v$};
    },
    methods: {
        storeLead(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/problemsalesLead`,this.data)
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
            this.data.name = '';
            this.data.address = '';
            this.data.phone = '';
            this.data.description = '';
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
