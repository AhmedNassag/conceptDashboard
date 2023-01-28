<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t(`global.offerDiscount`) }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexDiscountOffer'}">{{ $t(`global.offerDiscount`) }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t(`global.addDiscount`) }}</li>
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
                                    :to="{name: 'indexDiscountOffer'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['type']">{{ errors['type'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['value']">{{ errors['value'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['start_date']">{{ errors['start_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['expire_date']">{{ errors['expire_date'][0] }}<br /> </div>
                                    <form @submit.prevent="storeSupplier" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{ $t('global.Name') }}</label>
                                                <input
                                                   type="text" class="form-control"
                                                   v-model.trim="v$.name.$model"
                                                   id="validationCustom01"
                                                   placeholder="name"
                                                   :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">{{ $t('global.IsRequired') }}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t('global.type') }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.type.$model"
                                                    :class="{'is-invalid':v$.type.$error,'is-valid':!v$.type.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option value="fixed" >{{ $t('global.Fixed') }}</option>
                                                    <option value="percentage">{{ $t('global.Percentage') }}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.type.required.$invalid"> {{ $t('global.IsRequired') }}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.value') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim.number="v$.value.$model"
                                                       id="validationCustom03"
                                                       placeholder="القيمة"
                                                       :class="{'is-invalid':v$.value.$error,'is-valid':!v$.value.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.value.required.$invalid"> {{ $t('global.IsRequired') }}<br /> </span>
                                                    <span v-if="v$.value.numeric.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                </div>
                                            </div>

                                            <!--start start_date-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">{{ $t('global.startdate') }}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.start_date.$model"
                                                       id="validationCustom06"
                                                       :class="{'is-invalid':v$.start_date.$error,'is-valid':!v$.start_date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>
                                            <!--end start_date-->

                                            <!--start expire_date-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom07">{{ $t('global.enddate') }}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.expire_date.$model"
                                                       id="validationCustom07"
                                                       :placeholder="$t('global.startdate')"
                                                       :class="{'is-invalid':v$.expire_date.$error,'is-valid':!v$.expire_date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.expire_date.required.$invalid"> {{ $t('global.IsRequired') }}<br /> </span>
                                                </div>
                                            </div>
                                            <!--end expire_date-->
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
import {required,minLength,between,maxLength,alpha,numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "createDepartment",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);
        const {t} = useI18n({});

        //start design
        let addSupplier =  reactive({
            data:{
                name : '',
                type : '',
                value : null,
                start_date : new Date().toISOString().split('T')[0],
                expire_date : '',
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    required
                },
                type: {
                    required
                },
                value: {
                    numeric,
                    required
                },
                expire_date: {
                    required
                },
                start_date:{},
            }
        });


        const v$ = useVuelidate(rules,addSupplier.data);


        return {t,loading,...toRefs(addSupplier),v$};
    },
    methods: {
        storeSupplier(){

            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/offerDiscount`,this.data)
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
            this.data.type = '';
            this.data.value = '';
            this.data.expire_date = '';
            this.data.start_date = '';
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

.custom-textarea {
    height: 150px;
}
</style>
