<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Comments')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexLeadComment', params: {lang: locale || 'ar',id:idTarget}}">{{$t('global.Comments')}}</router-link></li>
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
                                    :to="{name: 'indexLeadComment', params: {lang: locale || 'ar',id:idTarget}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['comment']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['lead_id']">{{ errors['lead_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['employee_id']">{{ errors['employee_id'][0] }}<br /> </div>

                                    <form @submit.prevent="editLead" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-12 mb-6">
                                                <label for="validationCustom01">{{$t('global.Comment')}}</label>
                                                <textarea  class="form-control"
                                                           v-model.trim="v$.comment.$model"
                                                           id="validationCustom01"
                                                           :placeholder="$t('global.Comment')"
                                                           :class="{'is-invalid':v$.comment.$error,'is-valid':!v$.comment.$invalid}"
                                                ></textarea>

                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.comment.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.comment.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.comment.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.comment.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.comment.maxLength.$params.max }} {{$t('global.Letters')}} </span>
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


        let getLead = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/leadComment/${idLead.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addLead.data.comment = l.lead.comment;
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
                comment: '',
                lead_id: idTarget
            }
        });

        const rules = computed(() => {
            return {
                comment: {
                    minLength: minLength(3),
                    maxLength:maxLength(200),
                    required
                },
            }
        });


        const v$ = useVuelidate(rules,addLead.data);


        return {t,idTarget,loading,...toRefs(addLead),v$};
    },
    methods: {
        editLead(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/leadComment/${this.idLead}`,this.data)
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
