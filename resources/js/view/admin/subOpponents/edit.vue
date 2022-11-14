<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Opponents')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>

                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexOpponents', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Opponents') }}
                                </router-link>
                            </li>

                            <li :class="['breadcrumb-item']" v-for="(it,key) in mainData">
                                <router-link  :to="{name: 'indexSubOpponents', params: {lang: locale || 'ar',id:it.id,mainId:2}}">
                                    {{ it.name }}
                                </router-link>
                            </li>

                            <li class="breadcrumb-item">
                                {{$t('global.Edit')}} {{subData.name}}
                            </li>

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
                                    :to="{name: 'indexSubOpponents', params: {lang: locale || 'ar',id:parentId,mainId:2}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <form @submit.prevent="editIncome" class="needs-validation">
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
                                                <label>{{$t('global.AccountType')}}</label>
                                                <select v-model="data.debit" class="form-select">
                                                    <option  :value="1">{{$t('global.Debit')}}</option>
                                                    <option  :value="0">{{$t('global.Creditor')}}</option>
                                                </select>
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
    name: "edit",
    data(){
        return {
            errors:{}
        }
    },
    props:['mainId','id'],
    setup(props){
        const emitter = inject('emitter');

        const {id,mainId} = toRefs(props);
        const {t} = useI18n({});
        let income = ref({});
        let loading = ref(false);
        let mainData = ref([]);
        let subData = ref({});
        let parentId = ref(0);

        let getMainIncomeViews = () => {
            loading.value = true;
            console.log(id.value);
            adminApi.get(`/v1/dashboard/editSubAccount/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    addIncome.data.name = l.income.name;
                    addIncome.data.debit = l.income.debit;
                    mainData.value = l.data;
                    subData.value = l.income;
                    parentId.value = l.income.sub_account_id;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainIncomeViews();
        });


        //start design
        let addIncome =  reactive({
            data:{
                name : '',
                debit:0
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                }
            }
        });


        const v$ = useVuelidate(rules,addIncome.data);


        return {parentId,subData,id,mainId,mainData,t,loading,...toRefs(addIncome),v$};
    },
    methods: {
        editIncome(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/updateSubAccount/${this.id}`,this.data)
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
