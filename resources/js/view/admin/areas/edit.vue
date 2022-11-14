<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.areas')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexArea'}">{{$t('global.areas')}}</router-link></li>
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
                                    :to="{name: 'indexArea'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['shipping_price']">{{ errors['shipping_price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['province_id']">{{ errors['province_id'][0] }}<br /> </div>

                                    <form @submit.prevent="editArea" class="needs-validation">
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
                                                <label>{{ $t('global.ChooseProvinces') }}</label>

                                                <select v-model="data.province_id" :class="['form-select',{'is-invalid':v$.province_id.$error,'is-valid':!v$.province_id.$invalid}]">
                                                    <option v-for="province in provinces" :kay="province.id" :value="province.id">{{province.name}}</option>
                                                </select>
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.province_id.required.$invalid">{{$t('global.ProvincesIsRequired')}}<br /> </span>
                                                </div>

                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.shippingPrice') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.trim="v$.shipping_price.$model"
                                                       :class="{'is-invalid':v$.shipping_price.$error,'is-valid':!v$.shipping_price.$invalid}"
                                                       :placeholder="$t('global.shippingPrice')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.shipping_price.required.$invalid">{{ $t('global.IsRequired') }} <br/></span>
                                                    <span v-if="v$.shipping_price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
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
import {required, minLength, maxLength, numeric} from '@vuelidate/validators';
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
        let provinces = ref([]);


        let getMainAreaViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/area/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addArea.data.name = l.area.name;
                    addArea.data.province_id = l.area.province_id;
                    addArea.data.shipping_price = l.area.shipping_price;
                    provinces.value= l.provinces;
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
                name:'',
                shipping_price:0,
                province_id:''
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                shipping_price:{
                    required,
                    numeric
                },
                province_id: {required}
            }
        });


        const v$ = useVuelidate(rules,addArea.data);


        return {t,provinces,loading,...toRefs(addArea),v$};
    },
    methods: {
        editArea(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/area/${this.id}`,this.data)
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
