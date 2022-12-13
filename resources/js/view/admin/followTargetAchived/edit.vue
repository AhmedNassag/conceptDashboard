<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.TargetAchieved')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexfollowTargetAchieved', params: {lang: locale || 'ar',id:idTarget}}">
                                    {{$t('global.TargetAchieved')}}
                                </router-link>
                            </li>
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
                                <router-link :to="{name: 'indexfollowTargetAchieved', params: {lang: locale || 'ar',id:idTarget}}" class="btn btn-custom btn-dark">
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['address'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['date']">{{ errors['date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['count']">{{ errors['email'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['employee_id']">{{ errors['employee_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['seller_category_id']">{{ errors['seller_category_id'][0] }}<br /> </div>

                                    <form @submit.prevent="editLead" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.ChoseEmployee')}}</label>
                                                <select v-model="v$.employee_id.$model" class="form-select" :class="{'is-invalid':v$.employee_id.$error,'is-valid':!v$.employee_id.$invalid}">
                                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{employee.user.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.employee_id.required.$invalid">{{$t('global.EmployeeIsRequired')}} <br /></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3"  v-if="idTarget > 4">
                                                <label for="validationCustom02">{{$t('global.Amount')}}</label>
                                                <input type="number" step="0.1"
                                                       class="form-control"
                                                       v-model="v$.amount.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                       :placeholder="$t('global.Amount')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{$t('global.AmountIsRequired')}} <br /></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3" v-if="idTarget == 1 || idTarget == 2 || idTarget == 3 || idTarget == 4">
                                                <label for="validationCustom03">{{$t('global.Count')}}</label>
                                                <input type="number" step="1"
                                                       class="form-control"
                                                       v-model="v$.count.$model"
                                                       id="validationCustom03"
                                                       :class="{'is-invalid':v$.count.$error,'is-valid':!v$.count.$invalid}"
                                                       :placeholder="$t('global.Count')"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.count.required.$invalid">{{$t('global.PercentageIsRequired')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.ToDate')}}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.date.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.ToDate')"
                                                       :class="{'is-invalid':v$.date.$error,'is-valid':!v$.date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.date.required.$invalid">{{$t('global.NameEnIsRequired')}}<br /> </span>
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
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:["idTarget","idLead"],
    setup(props){
        const emitter = inject('emitter');

        const {idTarget,idLead} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let employees = ref([]);


        let getLead = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/followtargetAchieved/${idLead.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l.employees);
                    employees.value = l.employees;
                    addLead.data.date = l.target.date;
                    addLead.data.count = l.target.count;
                    addLead.data.amount = l.target.amount;
                    addLead.data.employee_id = l.target.employee_id;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getLead();
        });


        //start design
        let addLead =  reactive({
            data:{
                employee_id: '',
                date:'',
                amount:0,
                count: 0,
                seller_category_id: idTarget
            }
        });

        const rules = computed(() => {
            return {
                employee_id: {required},
                amount: {required},
                count: {required},
                date: {required},
            }
        });


        const v$ = useVuelidate(rules,addLead.data);


        return {t,employees,idTarget,loading,...toRefs(addLead),v$};
    },
    methods: {
        editLead(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/followtargetAchieved/${this.idLead}`,this.data)
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
