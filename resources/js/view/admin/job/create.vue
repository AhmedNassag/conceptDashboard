<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Jobs')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexJob'}">{{$t('global.Jobs')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('job.CreateJob')}}</li>
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
                                    :to="{name: 'indexJob'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['en.name']">{{ errors['en.name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['ar.name']">{{ errors['ar.name'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.NameEn')}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.NameEn')"
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
                                                <label for="validationCustom0">{{$t('global.AllowAddingToSalesTeam')}}</label>
                                                <input type="checkbox" id="validationCustom0" v-model="data.Allow_adding_to_sales_team" class="m-5" >
                                            </div>

<!--                                            <div class="col-md-6 mb-12" v-if="data.Allow_adding_to_sales_team">-->
<!--                                                <label>طريقه البيع</label>-->

<!--                                                <select v-model="data.selling_method_id" :class="['form-select',{'is-invalid':v$.selling_method_id.$error,'is-valid':!v$.selling_method_id.$invalid}]">-->
<!--                                                    <option v-for="selling_method in selling_methods" :key="selling_method.id" :value="selling_method.id">{{selling_method.name}}</option>-->
<!--                                                </select>-->

<!--                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>-->
<!--                                                <div class="invalid-feedback">-->
<!--                                                    <span class="text-danger" v-if="v$.selling_method_id.requiredIf.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>-->
<!--                                                </div>-->
<!--                                            </div>-->

<!--                                            <div class="col-md-6 mb-12" v-if="data.Allow_adding_to_sales_team">-->
<!--                                                <label>نوع البيع</label>-->

<!--                                                <select v-model="data.targetType" :class="['form-select',{'is-invalid':v$.targetType.$error,'is-valid':!v$.targetType.$invalid}]">-->
<!--                                                    <option value="0">{{$t('global.In Door')}}</option>-->
<!--                                                    <option value="1">{{$t('global.Out Door')}}</option>-->
<!--                                                </select>-->

<!--                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>-->
<!--                                                <div class="invalid-feedback">-->
<!--                                                    <span class="text-danger" v-if="v$.targetType.requiredIf.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>-->
<!--                                                </div>-->
<!--                                            </div>-->

                                        </div>

                                        <button class="btn btn-primary my-2" type="submit">{{$t('global.Submit')}}</button>
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
import {required, minLength, between, maxLength, alpha, integer, requiredIf} from '@vuelidate/validators';
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
        let selling_methods = ref([]);

        let addJob =  reactive({
            data:{
                name : '',
                Allow_adding_to_sales_team:0,
                // selling_method_id: '',
                // targetType: '',
            }
        });

        let getMainJobViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/job/create`)
            .then((res) => {
                let l = res.data.data;
                selling_methods.value = l.selling_methods;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        }

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                // selling_method_id: {
                //     requiredIf: requiredIf(addJob.data.Allow_adding_to_sales_team)
                // },
                // targetType: {
                //     requiredIf: requiredIf(addJob.data.Allow_adding_to_sales_team)
                // }
            }
        });

        onMounted(() => getMainJobViews());

        const v$ = useVuelidate(rules,addJob.data);


        return {t,loading,...toRefs(addJob),v$,selling_methods};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/job`,this.data)
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
            this.data.Allow_adding_to_sales_team = 0;
            // this.data.selling_method_id = '';
            // this.data.targetType = '';
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
