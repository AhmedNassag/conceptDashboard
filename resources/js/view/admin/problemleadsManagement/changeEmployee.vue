<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.problemLeadsManagement')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexProblemLeadsManagement', params: {lang: locale || 'ar'}}">
                                    {{$t('global.problemLeadsManagement')}}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('global.problemSalesEmployeeChange')}}</li>
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
                                <router-link :to="{name: 'indexProblemLeadsManagement', params: {lang: locale || 'ar'}}" class="btn btn-custom btn-dark">
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['employee_id']">{{ errors['employee_id'][0] }}<br /> </div>

                                    <form @submit.prevent="editLead" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.ChoseEmployee') }}</label>

                                                <select v-model="data.employee_id" :class="['form-select',{'is-invalid':v$.employee_id.$error,'is-valid':!v$.employee_id.$invalid}]">
                                                    <option v-for="employee in employees" :kay="employee.id" :value="employee.id">{{employee.user.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.employee_id.required.$invalid">{{$t('global.EmployeeIsRequired')}}<br /> </span>
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
    name: "change employee",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const emitter = inject('emitter');

        const {id} = toRefs(props)
        const {t} = useI18n({});
        let employees = ref([]);
        let loading = ref(false);


        let getLead = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/problemchangeEmployeeLead/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l);
                    employees.value= l.employees;
                    addLead.data.employee_id = l.lead.employee_id;
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
                employee_id: ''
            }
        });

        const rules = computed(() => {
            return {
                employee_id: {required},
            }
        });


        const v$ = useVuelidate(rules,addLead.data);


        return {t,employees,loading,...toRefs(addLead),v$};
    },
    methods: {
        editLead(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/problemupdateEmployeeLead/${this.id}`,this.data)
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
