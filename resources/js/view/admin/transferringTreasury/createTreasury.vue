<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.TransferringTreasury')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexTransferringTreasury', params: {lang: locale || 'ar'}}">{{$t('global.TransferringTreasury')}}</router-link></li>
                            <li class="breadcrumb-item active">{{$t('global.Add')}}</li>
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
                                    :to="{name: 'indexTransferringTreasury', params: {lang: locale || 'ar'}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('treasury.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['user_id']">{{ errors['user_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['from_treasury_id']">{{ errors['from_treasury_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['to_treasury_id']">{{ errors['to_treasury_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br /> </div>
                                    <form @submit.prevent="storePackage" class="needs-validation">

                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.TransferringForTreasury')}} <span v-if="data.from_treasury_id" class="amount">{{$t('global.Amount')}} : {{amount}}</span> </label>
                                                <select @change="amountTreasury" v-model="data.from_treasury_id" class="form-select" :class="{'is-invalid':v$.from_treasury_id.$error,'is-valid':!v$.from_treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.from_treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{$t('global.TransferringToTreasury')}} <span v-if="data.to_treasury_id" class="amount">{{$t('global.Amount')}} : {{amountTo}}</span> </label>
                                                <select @change="amountToTreasury" v-model="data.to_treasury_id" class="form-select" :class="{'is-invalid':v$.to_treasury_id.$error,'is-valid':!v$.to_treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.to_treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
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
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.notes.$model" :class="['form-control',{'is-invalid':v$.notes.$error,'is-valid':!v$.notes.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.notes.required.$invalid">{{$t('global.NotesIsRequired')}}<br /> </span>
                                                    <span v-if="v$.notes.minLength.$invalid">{{$t('global.NotesIsMustHaveAtLeast')}} {{ v$.notes.minLength.$params.max }} {{$t('global.Letters')}} <br /></span>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">{{$t('treasury.Submit')}}</button>
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
import {required,minLength,numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "createTreasury",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        const emitter = inject('emitter');
        const {t} = useI18n({});
        // get create treasury
        let treasuries = ref([]);
        let loading = ref(false);
        let amount = ref(0);
        let amountTo = ref(0);

        let getTreasuries = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeTreasury`)
                .then((res) => {
                    let l = res.data.data;
                    treasuries.value= l.treasuries;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

         onMounted(() => {
             getTreasuries();
        });

        emitter.on('get_lang', () => {
            getTreasuries();
        });

        //start design
        let addTreasury =  reactive({
            data:{
                from_treasury_id: null,
                to_treasury_id: null,
                amount: null,
                notes:null
            }
        });

        const rules = computed(() => {
            return {
                amount: {
                    required,
                    numeric

                },
                from_treasury_id:{
                    required
                },

                to_treasury_id:{
                    required
                },
                notes:{
                    required,
                    minLength: minLength(3),
                },

            }
        });

        let amountTreasury =()=>{
            let v = treasuries.value.filter((el)=> el.id == addTreasury.data.from_treasury_id);
            amount.value = v[0].amount;
        }
        let amountToTreasury =()=>{
            let v = treasuries.value.filter((el)=> el.id == addTreasury.data.to_treasury_id);
            amountTo.value = v[0].amount;
        }
        const v$ = useVuelidate(rules,addTreasury.data);

        return {t,treasuries,amountTreasury,amount,amountToTreasury,amountTo,getTreasuries,loading,...toRefs(addTreasury),v$};
    },
    methods: {
        storePackage(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/transferringTreasury`,this.data)
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
            this.data.to_treasury_id = '';
            this.data.from_treasury_id = '';
            this.data.notes = '';
            this.data.amount = '';
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
.amount{
    background-color: #fcb00c;
    color: #000;
    margin: 50px;
}
</style>
