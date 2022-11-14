<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.CreditCapacity')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexCreditCapacity'}">{{$t('global.CreditCapacity')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Edit')}}</li>
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
                                    :to="{name: 'indexCreditCapacity'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['start']">{{ errors['start'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['end']">{{ errors['end'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br /> </div>
                                    <form @submit.prevent="editArea" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{$t('global.StartsFrom')}}</label>
                                                <input type="number" class="form-control"
                                                       v-model="v$.start_amount.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.StartsFrom')"
                                                       :class="{'is-invalid':v$.start_amount.$error,'is-valid':!v$.start_amount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.start_amount.required.$invalid">{{$t('global.StartsFromIsRequired')}}<br /> </span>
                                                    <span v-if="v$.start_amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">{{$t('global.endsIn')}}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.end_amount.$model"
                                                       id="validationCustom04"
                                                       :placeholder="$t('global.endsIn')"
                                                       :class="{'is-invalid':v$.end_amount.$error,'is-valid':!v$.end_amount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.end_amount.required.$invalid">{{$t('global.endsInIsRequired')}}<br /> </span>
                                                    <span v-if="v$.end_amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Amount')}}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.amount.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Amount')"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{$t('global.AmountIsRequired')}}<br /> </span>
                                                    <span v-if="v$.amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
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
import {required,numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        const {t} = useI18n({});
        let loading = ref(false);


        let getMainAreaViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/creditCapacity/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addArea.data.start_amount = l.capacity.start_amount;
                    addArea.data.end_amount = l.capacity.end_amount;
                    addArea.data.amount = l.capacity.amount;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainAreaViews();
        });

        let addArea =  reactive({
            data:{
                start_amount:'',
                end_amount:'',
                amount:'',
            }
        });

        const rules = computed(() => {
            return {
                amount: {
                    required,
                    numeric
                },
                start_amount: {required,numeric},
                end_amount: {required,numeric},
            }
        });


        const v$ = useVuelidate(rules,addArea.data);


        return {t,loading,...toRefs(addArea),v$};
    },
    methods: {
        editArea(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/creditCapacity/${this.id}`,this.data)
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
