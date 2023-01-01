<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.wax') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexFilterWax'}">{{ $t('global.wax') }}</router-link></li>
                            <li class="breadcrumb-item active">تعديل</li>
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
                                    :to="{name: 'indexFilterWax'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['price']">{{ errors['price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>

                                    <form @submit.prevent="editFilterWax" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم الشمعه </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       placeholder="اسم الشمعه"
                                                       :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.name.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.name.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.price') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.price.$model"
                                                       id="validationCustom02"
                                                       :placeholder="$t('global.price')"
                                                       :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div> -->

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.model') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.model.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.price')"
                                                       :class="{'is-invalid':v$.model.$error,'is-valid':!v$.model.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.model.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.model.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.model.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.model.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.model.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <!--start type-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.type') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.type.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.type')"
                                                       :class="{'is-invalid':v$.type.$error,'is-valid':!v$.type.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.type.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.type.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.type.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.type.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.type.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end type-->

                                            <!--start origin-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.origin') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.origin.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.origin')"
                                                       :class="{'is-invalid':v$.origin.$error,'is-valid':!v$.origin.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.origin.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.origin.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.origin.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.origin.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.origin.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end origin-->

                                            <!--start period-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.period') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.period.$model"
                                                       id="validationCustom02"
                                                       :placeholder="$t('global.period')"
                                                       :class="{'is-invalid':v$.period.$error,'is-valid':!v$.period.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.period.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end period-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">تعديل</button>
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
import {required, minLength, maxLength, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editDepartment",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props)
        // get create Package
        let loading = ref(false);


        let getMeasure = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/filterWax/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addMeasure.data.name = l.filterWax.name;
                    addMeasure.data.price = l.filterWax.price;
                    addMeasure.data.model = l.filterWax.model;
                    addMeasure.data.type = l.filterWax.type;
                    addMeasure.data.origin = l.filterWax.origin;
                    addMeasure.data.period = l.filterWax.period;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMeasure();
        });


        //start Supplier
        let addMeasure =  reactive({
            data:{
                name : '',
                price: 0,
                model: '',
                type: '',
                origin: '',
                period: 0,
            }
        });

        const rules = computed(() => {
            return {
                price: {
                    required,
                    numeric
                },
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                model: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                type: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                origin: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                period: {
                    required,
                    numeric
                },
            }
        });


        const v$ = useVuelidate(rules,addMeasure.data);


        return {loading,...toRefs(addMeasure),v$};
    },
    methods: {
        editFilterWax(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/filterWax/${this.id}`,this.data)
                .then((res) => {

                    notify({
                        title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
                        type: "success",
                        duration: 5000,
                        speed: 2000
                    });

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
</style>
