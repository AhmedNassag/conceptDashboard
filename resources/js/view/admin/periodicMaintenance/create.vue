<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">عملاء الصيانة</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPeriodicMaintenance'}">عملاء الصيانة</router-link></li>
                            <li class="breadcrumb-item active">إضافة عميل صيانة</li>
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
                                    :to="{name: 'indexPeriodicMaintenance'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storePeriodicMaintenance" class="needs-validation">
                                        <div class="form-row row">

                                            <!--start name-->
                                            <div class="col-md-6 mb-3">
                                                <label>اسم </label>
                                                <input type="text" class="form-control"
                                                   v-model.trim="v$.name.$model"
                                                   placeholder="اسم "
                                                   :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.name.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.name.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end name-->

                                            <!--start phone-->
                                            <div class="col-md-6 mb-3">
                                                <label>رقم التليفون </label>
                                                <input type="text" class="form-control"
                                                   v-model.trim="v$.phone.$model"
                                                   placeholder="رقم التليفون"
                                                   :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.phone.integer.$invalid"> يجب ان يكون رقم.  <br /></span>
                                                </div>
                                            </div>
                                            <!--end phone-->

                                            <!--start province-->
                                            <div class="col-md-6 mb-3">
                                                <label>المحافظه</label>
                                                <select @change="getAreas(v$.province_id.$model)"
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.province_id.$model"
                                                    :class="{'is-invalid':v$.province_id.$error,'is-valid':!v$.province_id.$invalid}"
                                                >
                                                    <option
                                                        v-for="province in provinces"
                                                        :value=" province.id"
                                                        :key=" province.id"
                                                    >
                                                        {{ province.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.province_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end province-->

                                            <!--start area-->
                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t('global.choseArea') }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.area_id.$model"
                                                    :class="{'is-invalid':v$.area_id.$error,'is-valid':!v$.area_id.$invalid}"
                                                >
                                                    <option
                                                        v-for="area in areas"
                                                        :value=" area.id"
                                                        :key=" area.id"
                                                    >
                                                        {{ area.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.area_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.area_id.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end area-->

                                            <!--start address-->
                                            <div class="col-md-6 mb-3">
                                                <label>العنوان </label>
                                                <input type="text" class="form-control"
                                                   v-model.trim="v$.address.$model"
                                                   placeholder="العنوان"
                                                   :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end address-->

                                            <!--start waxes-->
                                            <div class="col-md-6 mb-3">
                                                <label>نوع الشمعة</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.wax_id.$model"
                                                    :class="{'is-invalid':v$.wax_id.$error,'is-valid':!v$.wax_id.$invalid}"
                                                >
                                                    <option
                                                        v-for="wax in waxes"
                                                        :value="wax.id"
                                                        :key="wax.id"
                                                    >
                                                        {{ wax.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.wax_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.wax_id.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end waxes-->

                                            <!--start number-->
                                            <div class="col-md-6 mb-3">
                                                <label>عدد الأجهزة</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.quantity.$model"
                                                    placeholder="عدد الأجهزة"
                                                    :class="{'is-invalid':v$.quantity.$error,'is-valid':!v$.quantity.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.quantity.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--start number-->

                                            <!--start next_maintenance-->
                                            <div class="col-md-6 mb-3">
                                                <label>موعد الصيانة المقترح</label>
                                                <input type="date" class="form-control"
                                                    v-model.trim="v$.next_maintenance.$model"
                                                    placeholder="موعد الصيانة القادم"
                                                    :class="{'is-invalid':v$.next_maintenance.$error,'is-valid':!v$.next_maintenance.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.next_maintenance.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end next_maintenance-->

                                        </div>

                                        <button class="btn btn-primary" type="submit">اضافه</button>
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
import {required, minLength, between, maxLength, alpha, integer, decimal} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "createPeriodicMaintenance",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);
        let waxes = ref([]);
        let areas = ref([]);
        let provinces = ref([]);

        //start design
        let addPeriodicMaintenance =  reactive({
            data:{
                name : '',
                quantity: '',
                next_maintenance: '',
                //
                phone: '',
                wax_id: '',
                province_id: '',
                area_id: '',
                address: '',
                //
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                quantity: {
                    required
                },
                next_maintenance: {
                    required
                },
                //
                phone: {
                    required,
                    integer
                },
                address: {
                    required
                },
                wax_id: {
                    required,
                    integer
                },
                province_id: {
                    required,
                    integer
                },
                area_id: {
                    required,
                    integer
                },
                //
            }
        });

        let getStore= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/create`)
            .then((res) => {
                let l = res.data.data;
                provinces.value = l.provinces;
                waxes.value = l.waxes;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        };
        onMounted(() => {
            getStore();
        });
        let getAreas= (id) => {
            loading.value = true;
            adminApi.get(`/v1/dashboard/province/${id}`)
            .then((res) => {
                let l = res.data.data;
                areas.value = l.areas;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        };

        const v$ = useVuelidate(rules,addPeriodicMaintenance.data);

        return {loading,...toRefs(addPeriodicMaintenance),v$,waxes,provinces,areas,getAreas};
    },
    methods: {
        storePeriodicMaintenance(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/periodicMaintenance`,this.data)
                .then((res) => {

                    notify({
                        title: `تم الاضافه بنجاح <i class="fas fa-check-circle"></i>`,
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
            this.data.name = '';
            this.quantity = '';
            this.next_maintenance = '';
            //
            this.phone = '';
            this.address = '';
            this.wax_id = '';
            this.province_id = '';
            this.area_id = '';
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
