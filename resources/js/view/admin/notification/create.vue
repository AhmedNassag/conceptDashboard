<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.addNotificationApp')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('global.addNotificationApp')}}</li>
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
                                    <div class="alert alert-danger text-center" v-if="errors['title']">{{ errors['title'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notification']">{{ errors['notification'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['product_id']">{{ errors['product_id'][0] }}<br /> </div>
                                    <form @submit.prevent="storeJob" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Title')}}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.title.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Title')"
                                                       :class="{'is-invalid':v$.title.$error,'is-valid':!v$.title.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.title.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.title.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.title.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.title.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.title.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChooseProduct') }}</label>

                                                <select2 v-model="data.product_id" :options="products" :class="[{'is-invalid':v$.product_id.$error,'is-valid':!v$.product_id.$invalid}]"/>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.product_id.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{$t('global.theNotification')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.notification.$model" :class="['form-control text-height',{'is-invalid':v$.notification.$error,'is-valid':!v$.notification.$invalid}]" :placeholder="$t('global.theNotification')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.notification.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                    <span v-if="v$.notification.minLength.$invalid">{{$t('global.IsMustHaveAtLeast')}} {{ v$.notification.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
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
    setup(){
        const {t} = useI18n({});
        let loading = ref(false);
        let products = ref([]);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/mobileProduct`)
                .then((res) => {
                    let l = res.data.data;
                    products.value= l.products;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getProducts();
        });

        let addJob =  reactive({
            data:{
                title : '',
                notification:'',
                product_id:''
            }
        });

        const rules = computed(() => {
            return {
                notification:{
                    required,
                    minLength: minLength(5),
                },
                title: {
                    minLength: minLength(3),
                    maxLength:maxLength(50),
                    required
                },
                product_id:{required}
            }
        });


        const v$ = useVuelidate(rules,addJob.data);


        return {t,products,loading,...toRefs(addJob),v$};
    },
    methods: {
        storeJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/notificationApp`,this.data)
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
            this.data.title = '';
            this.data.notification = '';
            this.data.product_id = '';
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
