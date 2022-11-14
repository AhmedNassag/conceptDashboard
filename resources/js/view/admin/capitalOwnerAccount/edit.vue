<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.capitalOwnerAccount')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexCapitalOwnerAccount'}">{{$t('global.capitalOwnerAccount')}}</router-link></li>
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
                                    :to="{name: 'indexCapitalOwnerAccount'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['payment_date']">{{ errors['payment_date'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['notes']">{{ errors['notes'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['lend']">{{ errors['lend'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['treasury_id']">{{ errors['treasury_id'][0] }}<br /> </div>
                                    <form @submit.prevent="editJob" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-4 mb-3">
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

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom10">{{ $t('global.Amount') }}</label>
                                                <input type="number" step=".01"
                                                       class="form-control"
                                                       v-model.number="v$.amount.$model"
                                                       id="validationCustom10"
                                                       :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                       :placeholder="$t('global.Amount')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.amount.required.$invalid">{{ $t('global.AmountIsRequired') }} <br/></span>
                                                    <span v-if="v$.amount.numeric.$invalid">{{$t('global.AmountIsNumeric')}} <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.ChooseType') }}</label>

                                                <select v-model="data.lend" :class="['form-select',{'is-invalid':v$.lend.$error,'is-valid':!v$.lend.$invalid}]">
                                                    <option value="1">اقراض</option>
                                                    <option value="0">اقتراض</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.lend.required.$invalid">{{$t('global.TypeIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('treasury.ChooseTreasury')}} <span v-if="data.treasury_id" class="amount">{{$t('global.Balance')}} : {{parseFloat(Math.round(treasury_amount))}}</span> </label>
                                                <select v-model="data.treasury_id" class="form-select" :class="{'is-invalid':v$.treasury_id.$error,'is-valid':!v$.treasury_id.$invalid}">
                                                    <option v-for="treasury in treasuries" :kay="treasury.id" :value="treasury.id">{{treasury.name}}</option>
                                                </select>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.treasury_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom01">{{$t('global.PaymentDate')}}</label>
                                                <input type="date" class="form-control"
                                                       v-model.trim="v$.payment_date.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.payment_date.$error,'is-valid':!v$.payment_date.$invalid}"
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.payment_date.required.$invalid">{{$t('global.PaymentDateIsRequired')}}<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('global.Notes')}}</label>
                                                <textarea rows="4" cols="5" v-model.trim="v$.notes.$model" :class="['form-control text-height',{'is-invalid':v$.notes.$error,'is-valid':!v$.notes.$invalid}]" :placeholder="$t('global.Notes')"></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.notes.required.$invalid">{{$t('global.DescriptionIsRequired')}}<br /> </span>
                                                    <span v-if="v$.notes.minLength.$invalid">{{$t('global.DescriptionIsMustHaveAtLeast')}} {{ v$.notes.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
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
import {computed, onMounted, reactive, toRefs, inject, ref, watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required,minLength,maxLength,numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editJob",
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
        let treasuries = ref([]);
        let treasury_amount = ref(0);

        let getMainJobViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/capitalOwnerAccount/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.data.name = l.account.name;
                    addJob.data.amount = l.account.amount;
                    addJob.data.lend = l.account.lend;
                    addJob.data.notes = l.account.notes;
                    addJob.data.payment_date = l.account.payment_date;
                    addJob.data.treasury_id = l.account.treasury_id;
                    treasuries.value = l.treasuries;
                    treasury_amount.value = l.treasuries.find((el)=> el.id == l.account.treasury_id).amount;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainJobViews();
        });

        let addJob =  reactive({
            data:{
                name : '',
                amount : '',
                lend : '',
                notes:'',
                payment_date: null,
                treasury_id:1,
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                notes:{
                    required,
                    minLength: minLength(5),
                },
                lend:{
                    required
                },
                treasury_id:{
                    required
                },
                amount:{
                    required,
                    numeric
                },
                payment_date:{
                    required,
                }
            }
        });

        watch(()=>addJob.data.treasury_id,(after,before) =>{
            let v = treasuries.value.find((el)=> el.id == addJob.data.treasury_id);
            treasury_amount.value = v.amount;
        });

        const v$ = useVuelidate(rules,addJob.data);


        return {t,loading,...toRefs(addJob),getMainJobViews,treasury_amount,treasuries,v$};
    },
    methods: {
        editJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/capitalOwnerAccount/${this.id}`,this.data)
                    .then((res) => {
                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.getMainJobViews();
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
