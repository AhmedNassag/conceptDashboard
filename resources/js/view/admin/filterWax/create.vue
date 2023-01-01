<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.wax') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexFilterWax'}">{{ $t('global.wax') }}</router-link></li>
                            <li class="breadcrumb-item active">اضافه</li>
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
                                    :to="{name: 'indexFilterWax'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['order_amount']">{{ errors['order_amount'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>

                                    <form @submit.prevent="storefilterWax" class="needs-validation">
                                        <div class="form-row row">

                                            <!--start name-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم الشمعه </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       placeholder="اسم الشمعه"
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

                                            <!--start price-->
                                            <!-- <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.price') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.price.$model"
                                                       id="validationCustom02"
                                                       :placeholder="$t('global.price')"
                                                       :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div> -->
                                            <!--end price-->

                                            <!--start model-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.model') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.model.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.model')"
                                                       :class="{'is-invalid':v$.model.$error,'is-valid':!v$.model.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.model.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.model.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.model.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.model.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.model.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end model-->

                                            <!--start type-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.type') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.type.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.type')"
                                                       :class="{'is-invalid':v$.type.$error,'is-valid':!v$.type.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.type.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.type.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.type.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.type.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.type.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end type-->

                                            <!--start origin-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.origin') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.origin.$model"
                                                       id="validationCustom03"
                                                       :placeholder="$t('global.origin')"
                                                       :class="{'is-invalid':v$.origin.$error,'is-valid':!v$.origin.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.origin.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.origin.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.origin.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.origin.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.origin.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end origin-->

                                            <!--start period-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.period') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model.trim="v$.period.$model"
                                                       id="validationCustom02"
                                                       :placeholder="$t('global.period')"
                                                       :class="{'is-invalid':v$.period.$error,'is-valid':!v$.period.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.period.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end period-->


                                            <hr class="col-md-12 mb-3"/>

                                            <!-----start product data----->
                                            <!--start barcode-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">الباركود </label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.barcode.$model"
                                                    id="validationCustom056"
                                                    placeholder="الباركود"
                                                    :class="{'is-invalid':v$.barcode.$error,'is-valid':!v$.barcode.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.barcode.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>
                                            <!--end barcode-->

                                            <!--start main_measurement_unit_id-->
                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الرئيسية</label>
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
                                                <label >عدد الوحدات داخل الفئة الفرعية </label>
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
                                                <label >البيع</label>
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
                                                <label for="validationCustom055">حد اعادة الطلب</label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model.trim="v$.Re_order_limit.$model"
                                                    id="validationCustom055"
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

                                            <!--start shipping-->
                                            <div class="col-md-6 mb-3">
                                                <label>مده الشحن</label>
                                                <input
                                                    type="text" class="form-control"
                                                    v-model.trim="v$.shipping.$model"
                                                    placeholder="مده الشحن"
                                                    :class="{'is-invalid':v$.shipping.$error,'is-valid':!v$.shipping.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.shipping.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end shipping-->

                                            <!--start guarantee-->
                                            <div class="col-md-6 mb-3">
                                                <label>الضمان</label>
                                                <input
                                                    type="text" class="form-control"
                                                    v-model.trim="v$.guarantee.$model"
                                                    placeholder=" الضمان "
                                                    :class="{'is-invalid':v$.guarantee.$error,'is-valid':!v$.guarantee.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.guarantee.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end guarantee-->

                                            <!--start description-->
                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustom034">الوصف</label>
                                                <textarea type="text" class="form-control custom-textarea"
                                                    v-model.trim="v$.description.$model"
                                                    id="validationCustom034"
                                                    placeholder="الوصف"
                                                    :class="{'is-invalid':v$.description.$error,'is-valid':!v$.description.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                            </div>
                                            <!--end description-->

                                            <!--start image-->
                                            <div class="col-md-3 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage"
                                                        type="file"
                                                        @change="preview"
                                                        id="mediaPackage"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">اقصي  حجم لا يتعدي 2mb</span>
                                                <p class="num-of-files">{{numberOfImage ? numberOfImage + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images" v-show="data.image && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--end image-->

                                            <!--start files-->
                                            <div class="col-md-9 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage[]"
                                                        type="file"
                                                        multiple
                                                        @change="preview2"
                                                        id="mediaPackage1"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">اقصي  حجم لا يتعدي 2mb</span>
                                                <p class="num-of-files">{{numberOfImage1 ? numberOfImage1 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images1" v-show="data.files && numberOfImage1"></div>
                                                <div class="container-images" v-show="!numberOfImage1">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--end files-->
                                            <!------end product data------>

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
import {required, minLength, between, maxLength, alpha, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "createDepartment",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);

        //
        let measures = ref([]);
        let sellingMethods = ref([]);
        //

        //start design
        let addSellingMethod =  reactive({
            data:{
                name : '',
                price: 0,
                model: '',
                type: '',
                origin: '',
                period: 0,

                //
                barcode: '',
                count_unit: null,
                description: '',
                image: {},
                files: [],
                maximum_product: null,
                Re_order_limit: null,
                main_measurement_unit_id: null,
                sub_measurement_unit_id: null,
                shipping: '',
                guarantee: '',
                selling_method: [],
                sell_app: 1,
                mainUnitMeasurement: '',
                subUnitMeasurement: '',
                //
            }
        });

        //
        let getProduct= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/create`)
            .then((res) => {
                let l = res.data.data;
                measures.value = l.measures;
                sellingMethods.value = l.sellingMethods;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        };
        //

        const rules = computed(() => {
            return {
                price: {
                    required,
                    numeric
                },
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                model: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                type: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                origin: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                period: {
                    required,
                    numeric
                },

                //
                barcode: {
                    integer
                },
                count_unit: {
                    required,
                    integer
                },
                Re_order_limit: {
                    required,
                    integer
                },
                shipping: {
                    required
                },
                guarantee: {
                    required
                },
                maximum_product: {
                    required,
                    integer
                },
                description: {},
                image: {
                    required
                },
                files: {
                    required
                },
                filterWaxes: {},
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


        const v$ = useVuelidate(rules,addSellingMethod.data);

        //
        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addSellingMethod.data.image = {};

            numberOfImage.value = e.target.files.length;

            addSellingMethod.data.image = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addSellingMethod.data.image.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addSellingMethod.data.image);

        };

        let preview2 = (e) => {

            let containerImages = document.querySelector('#container-images1');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addSellingMethod.data.files = [];

            numberOfImage1.value = e.target.files.length;

            for(let file of e.target.files){

                addSellingMethod.data.files.push(file);
                let reader = new FileReader();
                let figure = document.createElement('figure');
                let figcap = document.createElement('figcaption');

                figcap.innerText = file.name;
                figure.appendChild(figcap);

                reader.onload = () => {
                    let img = document.createElement('img');
                    img.setAttribute('src',reader.result);
                    figure.insertBefore(img,figcap);
                }

                containerImages.appendChild(figure);
                reader.readAsDataURL(file);
            }

        };

        const numberOfImage = ref(0);
        const numberOfImage1 = ref(0);

        onMounted(() => {
            getProduct();
        });
        //

        return {
            loading,
            ...toRefs(addSellingMethod),
            v$,
            //
            preview,
            preview2,
            numberOfImage,
            numberOfImage1,
            measures,
            sellingMethods,
            //
        };
    },
    methods: {
        storefilterWax(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();

                formData.append('name',this.data.name);
                formData.append('price',this.data.price);
                formData.append('model',this.data.model);
                formData.append('type',this.data.type);
                formData.append('origin',this.data.origin);
                formData.append('period',this.data.period);
                //
                formData.append('barcode',this.data.barcode);
                formData.append('count_unit',this.data.count_unit);
                formData.append('maximum_product',this.data.maximum_product);
                formData.append('Re_order_limit',this.data.Re_order_limit);
                formData.append('main_measurement_unit_id',this.data.main_measurement_unit_id);
                formData.append('sub_measurement_unit_id',this.data.sub_measurement_unit_id);
                formData.append('sell_app',this.data.sell_app);
                formData.append('selling_method',this.data.selling_method);
                formData.append('guarantee',this.data.guarantee);
                formData.append('shipping',this.data.shipping);
                formData.append('description',this.data.description);
                formData.append('image',this.data.image);
                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }
                //
                adminApi.post(`/v1/dashboard/filterWax`,formData)
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
            this.data.price = 0;
            this.data.model = '';
            this.data.type = '';
            this.data.origin = '';
            this.data.period = '';
            //
            document.querySelector('#container-images').innerHTML = '';
            document.querySelector('#container-images1').innerHTML = '';
            document.querySelector('#mediaPackage1').value = '';
            document.querySelector('#mediaPackage').value = '';
            this.numberOfImage = 0;
            this.numberOfImage1 = 0;
            this.data.barcode = '';
            this.data.count_unit = null;
            this.data.maximum_product = null;
            this.data.Re_order_limit = null;
            this.data.description = '';
            this.data.image= {};
            this.data.files = [];
            this.data.main_measurement_unit_id = null;
            this.data.sub_measurement_unit_id = null;
            this.data.selling_method = [];
            this.data.sell_app = 1;
            this.data.quantity = 0;
            this.data.sub_quantity = 0;
            this.data.mainUnitMeasurement = '';
            this.data.subUnitMeasurement = '';
            this.data.guarantee = '';
            this.data.shipping = '';
            //
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


.coustom-select {
    height: 100px;
}
.card{
    position: relative;
}

.waves-effect {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    width: 200px;
    height: 50px;
    text-align: center;
    line-height: 34px;
    margin: auto;
}

input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
    cursor: pointer;
    filter: alpha(opacity=0);
    opacity: 0;
}

.num-of-files{
    text-align: center;
    margin: 20px 0 30px;
}

.container-images {
    width: 90%;
    position: relative;
    margin: auto;
    display: flex;
    justify-content: space-evenly;
    gap: 20px;
    flex-wrap: wrap;
    padding: 10px;
    border-radius: 20px;
    background-color: #f7f7f7;
}
.custom-textarea {
    height: 150px;
}

.sec-body{
    border: 3px solid #103C67;
    border-radius: 20px;
    padding: 10px;
}
.sec-head{
    background-color: #103C67;
    color: #000;
    border-radius: 11px;
    padding: 5px;
    text-align: center;
    margin-bottom: 8px;
    margin-top: 10px;
}
.sec-body:hover .sec-head{
    border: 3px solid #103C67;
    padding: 2px;
    border-radius: 11px;
    background-color: #ffff;
}
.sec-head h3{
    font-weight: 700;
    color: #000;
}
</style>
