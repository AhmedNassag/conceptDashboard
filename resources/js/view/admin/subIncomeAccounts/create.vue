<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Incomes')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>

                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexIncomeAccounts', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Incomes') }}
                                </router-link>
                            </li>

                            <li :class="['breadcrumb-item']" v-for=" (it,key) in mainData">
                                <router-link  :to="{name: 'indexSubIncomeAccounts', params: {lang: locale || 'ar',id:it.id,mainId:4}}">
                                    {{ it.name }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item" >
                            </li>

                            <span v-for=" (it,key) in mainData" >
                            <li v-if="id == it.id" :class="['breadcrumb-item',(mainData.length-1) == key? 'active':'']" >

                                 {{$t('global.Add')}} {{ it.name }}
                            </li>
                                </span>

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
                                    :to="{name: 'indexSubIncomeAccounts', params: {lang: locale || 'ar',id:id,mainId:4}}"
                                    class="btn btn-custom btn-dark">
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <form @submit.prevent="storeIncome" class="needs-validation">
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
                                                <label >{{$t('global.AccountType')}}</label>
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
    name: "create",
    props:['mainId','id'],
    data(){
        return {
            errors:{}
        }
    },
    setup(props){
        const emitter = inject('emitter');
        const {t} = useI18n({});
        // get create Package
        let loading = ref(false);
        let mainData = ref([]);
        const {id,mainId} = toRefs(props);

        let getMAinSub = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/getMainSub/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    mainData.value = l.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getMAinSub();
        });

        emitter.on('get_lang', () => {
            getMAinSub(search.value);
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


        return {mainData,id,mainId,t,loading,...toRefs(addIncome),v$};
    },
    methods: {
        storeIncome(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/storeSubAccount/${this.mainId}/${this.id}`,this.data)
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
                        console.log(err);
                        // this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.name = '';
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
