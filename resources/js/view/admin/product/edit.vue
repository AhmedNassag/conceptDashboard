<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">المنتجات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexProduct'}">المنتجات</router-link></li>
                            <li class="breadcrumb-item active">تعديل المنتج</li>
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
                                    :to="{name: 'indexProduct'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editSupplier" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم المنتج </label>
                                                <input
                                                    type="text" class="form-control"
                                                    v-model.trim="v$.name.$model"
                                                    id="validationCustom01"
                                                    placeholder="اسم المنتج"
                                                    :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.name.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.name.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.name.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

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

                                            <div class="col-md-6 mb-3">
                                                <label >الفئه الرئيسية</label>
                                                <select @change="getSubCategory(v$.category_id.$model)"
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.category_id.$model"
                                                    :class="{'is-invalid':v$.category_id.$error,'is-valid':!v$.category_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="category in categories" :value="category.id" >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.category_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >الفئه الفرعية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_category_id.$model"
                                                    :class="{'is-invalid':v$.sub_category_id.$error,'is-valid':!v$.sub_category_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="category in subCategories" :value="category.id" >
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_category_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >الشركات</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.company_id.$model"
                                                    :class="{'is-invalid':v$.company_id.$error,'is-valid':!v$.company_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="company in companies" :value="company.id" >
                                                        {{ company.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.company_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الرئيسية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.main_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.main_measurement_unit_id.$error,'is-valid':!v$.main_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.main_measurement_unit_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >عدد الوحدات داخل الفئة الفرعية </label>
                                                <input
                                                    type="number" class="form-control"
                                                    v-model="v$.count_unit.$model"
                                                    placeholder="عدد الوحدات داخل الفئة الفرعية"
                                                    :class="{'is-invalid':v$.count_unit.$error,'is-valid':!v$.count_unit.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.count_unit.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.count_unit.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >وحدة القياس الفرعية</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.sub_measurement_unit_id.$model"
                                                    :class="{'is-invalid':v$.sub_measurement_unit_id.$error,'is-valid':!v$.sub_measurement_unit_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option v-for="measure in measures" :value="measure.id" >
                                                        {{ measure.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.sub_measurement_unit_id.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >طرق البيع</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    multiple
                                                    v-model="v$.selling_method.$model"
                                                    :class="{'is-invalid':v$.selling_method.$error,'is-valid':!v$.selling_method.$invalid}"
                                                >
                                                    <option v-for="sellingMethod in sellingMethods" :value="sellingMethod.id" >
                                                        {{ sellingMethod.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.selling_method.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>


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

                                            <div class="col-md-12 mb-3">
                                                <label for="validationCustom034">الوصف</label>
                                                <textarea type="text" class="form-control custom-textarea"
                                                          v-model.trim="v$.description.$model"
                                                          id="validationCustom034"
                                                          placeholder="الوصف"
                                                          :class="{'is-invalid':v$.description.$error,'is-valid':!v$.description.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.description.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

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
                                                            <img :src="`/upload/product/${image}`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>

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
                                                <div class="container-images">
                                                    <figure style="width: 100%;" class="row">
                                                        <figcaption v-for="(file,index) in images" style="position: relative;margin-top: 8px" class="col-4">
                                                            <a href="#" class="delete" @click.prevent="deleteOne(file.id,index)">X</a>
                                                            <img
                                                                style="width: 100%"
                                                                :src="`${file.file_name}`"
                                                            >
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3 mt-5">
                                                <div class="sec-body row">
                                                    <div class="col-md-12 mb-12 sec-head">
                                                        <h3>
                                                            {{ $t('global.TheBalanceOfTheFirstDuration') }}
                                                        </h3>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{$t('global.RequiredQuantity')}} ( {{data.mainUnitMeasurement}} ) ( {{$t('global.TotalAccount')}} )</label>
                                                        <input type="number" class="form-control"
                                                               v-model.number="v$.quantity.$model"
                                                               :placeholder="$t('global.RequiredQuantity') + '(' + data.mainUnitMeasurement +')'"
                                                               :class="{'is-invalid':v$.quantity.$error,'is-valid':!v$.quantity.$invalid}"
                                                        >
                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            <span v-if="v$.quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{$t('global.price')}} ( {{data.mainUnitMeasurement}} ) ( {{$t('global.TotalAccount')}} )</label>
                                                        <input type="number" step="0.1" class="form-control"
                                                               @input="subPrice"
                                                               v-model.number="v$.price.$model"
                                                               :placeholder="$t('global.price') +' ('+ data.mainUnitMeasurement +')'"
                                                               :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                        >
                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.price.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            <span v-if="v$.price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{$t('global.RequiredQuantity')}} ( {{data.subUnitMeasurement}} ) ( {{$t('global.Partial')}} )</label>
                                                        <input type="number" class="form-control"
                                                               v-model.number="v$.sub_quantity.$model"
                                                               :placeholder="$t('global.RequiredQuantity') + '(' + data.subUnitMeasurement +')'"
                                                               :class="{'is-invalid':v$.sub_quantity.$error,'is-valid':!v$.sub_quantity.$invalid}"
                                                        >
                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.sub_quantity.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            <span v-if="v$.sub_quantity.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{$t('global.price')}} ( {{data.subUnitMeasurement}} ) ( {{$t('global.Partial')}} )</label>
                                                        <input type="number" step="0.1" class="form-control" disabled
                                                               v-model.number="v$.sub_price.$model"
                                                               :placeholder="$t('global.price') +' ('+ data.subUnitMeasurement +')'"
                                                               :class="{'is-invalid':v$.sub_price.$error,'is-valid':!v$.sub_price.$invalid}"
                                                        >
                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.sub_price.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                            <span v-if="v$.sub_price.numeric.$invalid">{{$t('global.ThisFieldIsNumeric')}} <br /></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3 mb-3">
                                                        <label>{{ $t('global.ChooseStore') }}</label>

                                                        <select v-model="data.store_id" :class="['form-select',{'is-invalid':v$.store_id.$error,'is-valid':!v$.store_id.$invalid}]">
                                                            <option v-for="store in stores" :kay="store.id" :value="store.id">{{store.name}}</option>
                                                        </select>
                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.store_id.required.$invalid">{{$t('global.StoreIsRequired')}}<br /> </span>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

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
import {computed, onMounted, reactive, toRefs, ref, watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, integer, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editDepartment",
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
        let companies = ref([]);
        let categories = ref([]);
        let subCategories = ref([]);
        let measures = ref([]);
        let sellingMethods = ref([]);
        let stores = ref([]);
        let images = ref([]);
        let image = ref('');

        //start design
        let addProduct =  reactive({
            data:{
                name : '',
                barcode : '',
                maximum_product: null,
                Re_order_limit: null,
                description : '',
                image : {},
                files : [],
                category_id: null,
                sub_category_id: null,
                company_id: null,
                main_measurement_unit_id: null,
                sub_measurement_unit_id: null,
                count_unit : null,
                selling_method: [],
                sell_app:1,
                quantity:0,
                sub_quantity:0,
                price:0,
                sub_price:0,
                mainUnitMeasurement:'',
                subUnitMeasurement:'',
                store_id:1,
            }
        });

        let getProduct = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/product/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addProduct.data.name = l.product.name;
                    addProduct.data.barcode = l.product.barcode;
                    addProduct.data.maximum_product= l.product.maximum_product;
                    addProduct.data.Re_order_limit= l.product.Re_order_limit;
                    addProduct.data.description = l.product.description;
                    addProduct.data.category_id = l.product.category_id;
                    addProduct.data.sub_category_id = l.product.sub_category_id;
                    addProduct.data.company_id = l.product.company_id;
                    addProduct.data.count_unit = l.product.count_unit;
                    addProduct.data.sell_app = l.product.sell_app;
                    addProduct.data.main_measurement_unit_id = l.product.main_measurement_unit_id;
                    addProduct.data.sub_measurement_unit_id = l.product.sub_measurement_unit_id;

                    addProduct.data.quantity = l.storeProduct.quantity;
                    addProduct.data.sub_quantity = l.storeProduct.sub_quantity;
                    addProduct.data.price = l.purchaseProducts.price;
                    addProduct.data.sub_price =  parseFloat(l.purchaseProducts.price / l.product.count_unit).toFixed(2);
                    addProduct.data.store_id = l.storeProduct.store_id;

                    image.value = l.product.image;
                    images.value = l.product.media;
                    companies.value = l.companies;
                    categories.value = l.categories;
                    measures.value = l.measures;
                    stores.value = l.stores;
                    sellingMethods.value = l.sellingMethods;
                    l.sellingMethodChange.forEach((e) => {
                        addProduct.data.selling_method.push(e.id);
                    });
                    getSubCategory(l.product.category_id);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        onMounted(() => {
            getProduct();
        });

        let getSubCategory= (id) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/category/${id}`)
                .then((res) => {
                    let l = res.data.data;
                    subCategories.value = l.subCategories;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                barcode: {
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
                description: {required},
                category_id: {
                    required,
                    integer
                },
                sub_category_id: {
                    required,
                    integer
                },
                company_id: {
                    required,
                    integer
                },
                main_measurement_unit_id: {
                    required,
                    integer
                },
                sub_measurement_unit_id: {
                    required,
                    integer
                },
                count_unit: {
                    required,
                    integer
                },
                selling_method: {
                    required
                },
                sell_app: {
                    required
                },
                price: {
                    required,
                    numeric
                },
                sub_price: {
                    required,
                    numeric
                },
                quantity: {
                    required,
                    numeric
                },
                sub_quantity: {
                    required,
                    numeric
                },
                store_id:{
                    required
                },
            }
        });

        const v$ = useVuelidate(rules,addProduct.data);

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addProduct.data.image = {};

            numberOfImage.value = e.target.files.length;

            addProduct.data.image = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addProduct.data.image.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addProduct.data.image);

        };

        let preview2 = (e) => {

            let containerImages = document.querySelector('#container-images1');
            if(numberOfImage1.value){
                containerImages.innerHTML = '';
            }
            addProduct.data.files = [];

            numberOfImage1.value = e.target.files.length;

            for(let file of e.target.files){

                addProduct.data.files.push(file);
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

        let deleteOne = (idMedia,index) =>{
            loading.value = true;

            adminApi.post(`/v1/dashboard/deleteOne/${id.value}`,{idMedia:idMedia})
                .then((res) => {
                    images.value.splice(index,1);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        let subPrice = ()=>{
            addProduct.data.sub_price = parseFloat(addProduct.data.price / addProduct.data.count_unit).toFixed(2);
        }

        watch(()=>addProduct.data.main_measurement_unit_id,(after,before) =>{
            let v = measures.value.filter((el)=> el.id == addProduct.data.main_measurement_unit_id);
            if (v.length > 0){
                addProduct.data.mainUnitMeasurement = v[0].name;
            }
        });

        watch(()=>addProduct.data.sub_measurement_unit_id,(after,before) =>{
            let v = measures.value.filter((el)=> el.id == addProduct.data.sub_measurement_unit_id);
            if (v.length > 0){
                addProduct.data.subUnitMeasurement = v[0].name;
            }
        });


        return { loading,
            ...toRefs(addProduct),
            v$,
            preview,
            preview2,
            subPrice,
            numberOfImage,
            numberOfImage1,
            companies,
            categories,
            stores,
            measures,
            sellingMethods,
            image,
            images,
            deleteOne,
            subCategories,
            getSubCategory,
            id
        };
    },
    methods: {
        editSupplier(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append('quantity',this.data.quantity);
                formData.append('store_id',this.data.store_id);
                formData.append('sub_quantity',this.data.sub_quantity);
                formData.append('price',this.data.price);
                formData.append('sub_price',this.data.sub_price);
                formData.append('name',this.data.name);
                formData.append('barcode',this.data.barcode);
                formData.append('maximum_product',this.data.maximum_product);
                formData.append('Re_order_limit',this.data.Re_order_limit);
                formData.append('description',this.data.description);
                formData.append('category_id',this.data.category_id);
                formData.append('company_id',this.data.company_id);
                formData.append('sell_app',this.data.sell_app);
                formData.append('sub_category_id',this.data.sub_category_id);
                formData.append('main_measurement_unit_id',this.data.main_measurement_unit_id);
                formData.append('sub_measurement_unit_id',this.data.sub_measurement_unit_id);
                formData.append('count_unit',this.data.count_unit);
                formData.append('image',this.data.image);
                formData.append('selling_method',this.data.selling_method);
                formData.append('_method','PUT');

                for( var i = 0; i < this.numberOfImage1; i++ ){
                    let file = this.data.files[i];
                    formData.append('files[' + i + ']', file);
                }

                adminApi.post(`/v1/dashboard/product/${this.id}`,formData)
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

.delete {
    position: absolute;
    top: 6px;
    left: 23px;
    font-size: 16px;
    padding: 0px 5px;
    text-align: center;
    border: 1px solid;
    border-radius: 50%;
}
.sec-body{
    border: 3px solid #fcb00c;
    border-radius: 20px;
    padding: 10px;
}
.sec-head{
    background-color: #fcb00c;
    color: #000;
    border-radius: 11px;
    padding: 5px;
    text-align: center;
    margin-bottom: 8px;
    margin-top: 10px;
}
.sec-body:hover .sec-head{
    border: 3px solid #fcb00c;
    padding: 2px;
    border-radius: 11px;
    background-color: #ffff;
}
.sec-head h3{
    font-weight: 700;
}

</style>
