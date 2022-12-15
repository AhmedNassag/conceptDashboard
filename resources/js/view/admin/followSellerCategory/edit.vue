<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.EmployeesTargets')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexfollowSellerCategory', params: {lang: locale || 'ar'}}">
                                    {{$t('global.EmployeesTargets')}}
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
                                <router-link :to="{name: 'indexfollowSellerCategory', params: {lang: locale || 'ar'}}" class="btn btn-custom btn-dark">
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['seller_category_id']">{{ errors['seller_category_id'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['employee_id']">{{ errors['employee_id'][0] }}<br /> </div>
                                    <form @submit.prevent="editTarget" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6">
                                                <label>{{$t('global.ChoseEmployee')}}</label>
                                                <input type="text" class="form-control" :value="employee.name" disabled>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="WebsitePages">{{$t('global.ChoseCategory')}}</label>
                                                    <select
                                                        multiple
                                                        class="form-control coustom-select"
                                                        id="WebsitePages"
                                                        v-model="v$.seller_category_id.$model"
                                                        :class="{'is-invalid':v$.seller_category_id.$error,'is-valid':!v$.seller_category_id.$invalid}"
                                                        v-if="categories"
                                                    >
                                                        <option v-for="category in categories" :value="category.id" :key="category.id">
                                                            {{ category.name}}
                                                        </option>

                                                    </select>
                                                    <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                    <div class="invalid-feedback">
                                                        <span v-if="v$.seller_category_id.required.$invalid">{{$t('global.CategoryIsRequired')}}</span>
                                                    </div>
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
    props:["id"],
    setup(props){
        const emitter = inject('emitter');

        const {id} = toRefs(props)
        const {t} = useI18n({});
        // get create Package
        let categories = ref([]);
        let employee = ref({});
        let loading = ref(false);


        let getMainTargetViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/followsellerCategory/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    categories.value= l.categories;
                    employee.value = l.employee.user;
                    l.targets.forEach(el =>{
                        addTarget.data.seller_category_id.push(el.id);
                    })

                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainTargetViews();
        });


        //start design
        let addTarget =  reactive({
            data:{
                seller_category_id: [],
                employee_id: id.value,
            }
        });

        const rules = computed(() => {
            return {
                employee_id:{required},
                seller_category_id:{required},
            }
        });


        const v$ = useVuelidate(rules,addTarget.data);


        return {t,loading,...toRefs(addTarget),v$,categories,employee};
    },
    methods: {
        editTarget(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/followsellerCategory/${this.id}`,this.data)
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
