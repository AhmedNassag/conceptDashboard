<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.termsPrivacy')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexTermsPrivacy'}">{{$t('global.termsPrivacy')}}</router-link></li>
                            <li class="breadcrumb-item active">تعديل</li>
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
                                    :to="{name: 'indexTermsPrivacy'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['term']">{{ errors['term'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['privacy']">{{ errors['privacy'][0] }}<br /> </div>
                                    <form @submit.prevent="editTermsPrivacy" class="needs-validation">
                                        <div class="form-row row">

                                            <!--start term-->
                                            <div class="col-md-10 mb-3">
                                                <label for="validationCustom01">{{$t('global.Terms')}}</label>
                                                <textarea type="text" class="form-control" cols="20" rows="10"
                                                    v-model.trim="v$.term.$model"
                                                    id="validationCustom01"
                                                    :placeholder="$t('global.Terms')"
                                                    :class="{'is-invalid':v$.term.$error,'is-valid':!v$.term.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.term.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.term.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.term.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.term.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.term.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <!--end term-->

                                            <!--start privacy-->
                                            <div class="col-md-10 mb-3">
                                                <label for="validationCustom01">{{$t('global.Privacy')}}</label>
                                                <textarea type="text" class="form-control" cols="20" rows="10"
                                                    v-model.trim="v$.privacy.$model"
                                                    id="validationCustom02"
                                                    :placeholder="$t('global.Privacy')"
                                                    :class="{'is-invalid':v$.privacy.$error,'is-valid':!v$.privacy.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.privacy.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.privacy.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.privacy.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.privacy.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.privacy.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <!--end privacy-->

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
import {required,minLength,maxLength} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editTermsPrivacy",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        const {t} = useI18n({});
        // get create Package
        let termsPrivacy = ref({});
        let loading = ref(false);


        let getTermsPrivacy = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/termsPrivacy/${id.value}/edit`)
            .then((res) => {
                let l = res.data.data;
                addTermsPrivacy.data.term = l.termsPrivacy.term;
                addTermsPrivacy.data.privacy = l.termsPrivacy.privacy;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getTermsPrivacy();
        });


        //start design
        let addTermsPrivacy =  reactive({
            data:{
                term : '',
                privacy : '',
            }
        });

        const rules = computed(() => {
            return {
                term: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                privacy: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
            }
        });


        const v$ = useVuelidate(rules,addTermsPrivacy.data);


        return {t,loading,...toRefs(addTermsPrivacy),v$};
    },
    methods: {
        editTermsPrivacy(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/termsPrivacy/${this.id}`,this.data)
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
