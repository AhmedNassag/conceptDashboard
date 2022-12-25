<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">عملاء الصيانة</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPeriodicMaintenance'}">عملاء الصيانة</router-link></li>
                            <li class="breadcrumb-item active">إضافة عميل صيانة</li>
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
                                    :to="{name: 'indexPeriodicMaintenance'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storePeriodicMaintenance" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم العميل</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.name.$model"
                                                    id="validationCustom01"
                                                    placeholder="اسم العميل"
                                                    :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.name.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.name.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">عدد الأجهزة</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.quantity.$model"
                                                    id="validationCustom02"
                                                    placeholder="عدد الأجهزة"
                                                    :class="{'is-invalid':v$.quantity.$error,'is-valid':!v$.quantity.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.quantity.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">سعر الصيانة</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.price.$model"
                                                    id="validationCustom03"
                                                    placeholder="سعر الصيانة"
                                                    :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">موعد الصيانة القادم</label>
                                                <input type="date" class="form-control"
                                                    v-model.trim="v$.next_maintenance.$model"
                                                    id="validationCustom04"
                                                    placeholder="موعد الصيانة القادم"
                                                    :class="{'is-invalid':v$.next_maintenance.$error,'is-valid':!v$.next_maintenance.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.next_maintenance.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn btn-primary" type="submit">اضافه</button>
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


export default {
    name: "createPeriodicMaintenance",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);

        //start design
        let addPeriodicMaintenance =  reactive({
            data:{
                name : '',
                quantity: '',
                price: '',
                next_maintenance: '',
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                quantity: {
                    required
                },
                price: {
                    required
                },
                next_maintenance: {
                    required
                }
            }
        });


        const v$ = useVuelidate(rules,addPeriodicMaintenance.data);


        return {loading,...toRefs(addPeriodicMaintenance),v$};
    },
    methods: {
        storePeriodicMaintenance(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/periodicMaintenance`,this.data)
                    .then((res) => {

                        notify({
                            title: `تم الاضافه بنجاح <i class="fas fa-check-circle"></i>`,
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
            this.quantity = '';
            this.price = '';
            this.next_maintenance = '';
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
