<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.SpareAccessor') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSpareAccessor'}">{{ $t('global.SpareAccessor') }}</router-link></li>
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
                                    :to="{name: 'indexSpareAccessor'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['price']">{{ errors['order_amount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>

                                    <form @submit.prevent="editSellingMethod" class="needs-validation">
                                        <div class="form-row row">

                                            <!--start name-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم قطعه الإكسسوار </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       placeholder="اسم طريقه البيع"
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

                                            <hr class="col-md-12 mb-3"/>

                                            <!-----start product data----->

                                            <!--start main_measurement_unit_id-->
                                            <div class="col-md-6 mb-3">
                                                <label>وحدة القياس الرئيسية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.main_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.main_measurement_unit_id.$error,'is-valid':!v$.main_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.main_measurement_unit_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end main_measurement_unit_id-->

                                            <!---start count_unit-->
                                            <div class="col-md-6 mb-3">
                                                <label>عدد الوحدات داخل الفئة الفرعية</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.count_unit.$model"
                                                    @input="subPrice"
                                                    placeholder="عدد الوحدات داخل الفئة الفرعية"
                                                    :class="{'is-invalid':v$.count_unit.$error,'is-valid':!v$.count_unit.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.count_unit.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.count_unit.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end count_unit-->

                                            <!--start sub_measurement_unit_id-->
                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الفرعية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.sub_measurement_unit_id.$error,'is-valid':!v$.sub_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :key="measure.id" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_measurement_unit_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end sub_measurement_unit_id-->

                                            <!--start selling_method-->
                                            <div class="col-md-6 mb-3">
                                                <label >طرق البيع</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    multiple
                                                    v-model="v$.selling_method.$model"
                                                    :class="{'is-invalid':v$.selling_method.$error,'is-valid':!v$.selling_method.$invalid}"
                                                >
                                                    <option v-for="sellingMethod in sellingMethods" :key="sellingMethod.id" :value="sellingMethod.id" >
                                                        {{ sellingMethod.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.selling_method.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end selling_method-->

                                            <!--start Re_order_limit-->
                                            <div class="col-md-6 mb-3">
                                                <label>حد اعادة الطلب</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.Re_order_limit.$model"
                                                    placeholder="حد اعادة الطلب"
                                                    :class="{'is-invalid':v$.Re_order_limit.$error,'is-valid':!v$.Re_order_limit.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.Re_order_limit.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.Re_order_limit.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end Re_order_limit-->

                                            <!--start maximum_product-->
                                            <div class="col-md-6 mb-3">
                                                <label>اقصي كمية فى المخزن</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.maximum_product.$model"
                                                    placeholder="اقصي كمية فى المخزن"
                                                    :class="{'is-invalid':v$.maximum_product.$error,'is-valid':!v$.maximum_product.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.maximum_product.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.maximum_product.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end maximum_product-->

                                            <!--start sell_app-->
                                            <div class="col-md-6 mb-3">
                                                <label>اماكن ظهور المنتج</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sell_app.$model"
                                                    :class="{'is-invalid':v$.sell_app.$error,'is-valid':!v$.sell_app.$invalid}"
                                                >

                                                    <option value="1">{{$t('global.OfferInDirectSellingAndApplication')}}</option>
                                                    <option value="0">{{$t('global.OfferedForDirectSalesOnly')}}</option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sell_app.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end sell_app-->

                                            <!--start description-->
                                            <div class="col-md-12 mb-3">
                                                <label>الوصف</label>
                                                <textarea type="text" class="form-control custom-textarea"
                                                          v-model.trim="v$.description.$model"
                                                          placeholder="الوصف"
                                                          :class="{'is-invalid':v$.description.$error,'is-valid':!v$.description.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                            </div>
                                            <!--end description-->
                                            <!-----end product data----->

                                        </div>

                                        <button class="btn btn-primary" type="submit">تعديل</button>
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
import {required, minLength, maxLength, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editDeAccessorment",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){

        const {id} = toRefs(props)
        // get create Package
        let loading = ref(false);
        let measures = ref([]);
        let sellingMethods = ref([]);


        let getMeasure = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/spareAccessor/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addMeasure.data.name = l.spareAccessor.name;
                    addMeasure.data.price = l.spareAccessor.price;
                    //
                    addMeasure.data.main_measurement_unit_id = l.product.main_measurement_unit_id;
                    addMeasure.data.count_unit = l.product.count_unit;
                    addMeasure.data.sub_measurement_unit_id = l.product.sub_measurement_unit_id;
                    addMeasure.data.Re_order_limit= l.product.Re_order_limit;
                    addMeasure.data.maximum_product= l.product.maximum_product;
                    addMeasure.data.sell_app = l.product.sell_app;
                    addMeasure.data.description = l.product.description;
                    addMeasure.data.points= l.product.points;

                    measures.value = l.measures;
                    sellingMethods.value = l.sellingMethods;
                    l.sellingMethodChange.forEach((e) => {
                        addMeasure.data.selling_method.push(e.id);
                    });
                    //
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMeasure();
        });


        //start Supplier
        let addMeasure =  reactive({
            data:{
                name : '',
                price : 0,
                //
                count_unit: null,
                description: '',
                maximum_product: null,
                Re_order_limit: null,
                main_measurement_unit_id: null,
                sub_measurement_unit_id: null,
                shipping: 0,
                guarantee: 0,
                selling_method: [],
                sell_app: 1,
                mainUnitMeasurement: '',
                subUnitMeasurement: '',
                //
            }
        });

        const rules = computed(() => {
            return {
                price:{
                    required,
                    numeric
                },
                name: {
                    minLength: minLength(1),
                    maxLength:maxLength(70),
                    required
                },
                //
                count_unit: {
                    required,
                    integer
                },
                Re_order_limit: {
                    required,
                    integer
                },
                maximum_product: {
                    required,
                    integer
                },
                description: {},
                main_measurement_unit_id: {
                    required,
                    integer
                },
                sub_measurement_unit_id: {
                    required,
                    integer
                },
                selling_method: {
                    required
                },
                sell_app: {
                    required
                },
                //
            }
        });


        const v$ = useVuelidate(rules,addMeasure.data);


        return {
            loading,
            ...toRefs(addMeasure),
            v$,
            measures,
            sellingMethods,
        };
    },
    methods: {
        editSellingMethod(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/spareAccessor/${this.id}`,this.data)
                    .then((res) => {

                        notify({
                            title: `تم التعديل بنجاح <i class="fas fa-check-circle"></i>`,
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
