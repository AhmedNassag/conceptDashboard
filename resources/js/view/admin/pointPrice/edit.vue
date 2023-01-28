<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t(`global.pointPrice`) }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPointPrice'}">{{ $t(`global.pointPrice`) }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('global.editPointPrice') }}</li>
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
                                    :to="{name: 'indexPointPrice'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['value']">{{ errors['value'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['expire_date']">{{ errors['expire_date'][0] }}<br /> </div>
                                    <form @submit.prevent="editPointPrice" class="needs-validation">
                                        <div class="form-row row">
                                            <!--start value-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.value') }}</label>
                                                <input type="number" class="form-control"
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
                                            <!--end value-->

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
import {required, minLength, maxLength, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editDepartment",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props);
        const {t} = useI18n({});
        // get create Package
        let loading = ref(false);


        let getPointPrice = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/pointPrice/${id.value}/edit`)
            .then((res) => {
                let l = res.data.data;
                addPointPrice.data.value = l.pointPrice.value;
                addPointPrice.data.expire_date = l.pointPrice.expire_date ;
                console.log(l);
            })
            .catch((err) => {})
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getPointPrice();
        });


        //start design
        let addPointPrice =  reactive({
            data:{
                value : 0,
                expire_date : '',
            }
        });

        const rules = computed(() => {
            return {
                value: {
                    numeric,
                    required
                },
                expire_date: {
                    required
                },
            }
        });


        const v$ = useVuelidate(rules,addPointPrice.data);


        return {t,loading,...toRefs(addPointPrice),v$};
    },
    methods: {
        editPointPrice(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/pointPrice/${this.id}`,this.data)
                .then((res) => {
                    notify({
                        title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
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
