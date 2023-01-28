<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">العملاء</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexClient'}">العملاء</router-link></li>
                            <li class="breadcrumb-item active">اضافه عميل</li>
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
                                    :to="{name: 'indexClient'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['email']">{{ errors['email'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">{{ errors['address'][0] }}<br /> </div>

                                    <form @submit.prevent="storeClient" class="needs-validation">
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

                                            <!--start email-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">الاميل </label>
                                                <input type="email" class="form-control"
                                                       v-model.trim="v$.email.$model"
                                                       id="validationCustom02"
                                                       placeholder="الاميل"
                                                       :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.email.$invalid"> يجب ان يكون بريد الكترونى  <br /></span>
                                                </div>
                                            </div>
                                            <!--start email-->

                                            <!--start phone-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">رقم التليفون </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       id="validationCustom03"
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
                                                <label >المحافظه</label>
                                                <select @change="getAreas(v$.province_id.$model)"
                                                        name="type"
                                                        class="form-control"
                                                        v-model="v$.province_id.$model"
                                                        :class="{'is-invalid':v$.province_id.$error,'is-valid':!v$.province_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option
                                                        v-for="province in provinces"
                                                        :value=" province.id"
                                                        :key=" province.id"
                                                    >{{ province.name }}</option>
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
                                                    <option value="">---</option>
                                                    <option
                                                        v-for="area in areas"
                                                        :value=" area.id"
                                                        :key=" area.id"
                                                    >{{ area.name }}</option>
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
                                                <label for="validationCustom04">العنوان </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       id="validationCustom04"
                                                       placeholder="العنوان"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end area-->

                                            <div class="col-md-6 mb-3">
                                                <label>كيفية التعرف علينا</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.knowledge_way_id.$model"
                                                    :class="{'is-invalid':v$.knowledge_way_id.$error,'is-valid':!v$.knowledge_way_id.$invalid}"
                                                >
                                                    <option
                                                        v-for="knowledgeWay in knowledgeWays"
                                                        :value=" knowledgeWay.id"
                                                        :key=" knowledgeWay.id"
                                                    >{{ knowledgeWay.name }}</option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.knowledge_way_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.knowledge_way_id.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3 mt-5" style="display: none">
                                                <div class="sec-body">
                                                    <div class="col-md-12 mb-12 sec-head">
                                                        <h3>
                                                            {{ $t('global.TheBalanceOfTheFirstDuration') }}
                                                        </h3>
                                                    </div>
                                                    <div class="col-md-12 mb-12" >
                                                        <label for="validationCustom02">{{ $t('global.Amount') }}</label>
                                                        <input type="number" step="0.1"
                                                               class="form-control"
                                                               v-model.trim="v$.amount.$model"
                                                               id="validationCustom11"
                                                               :class="{'is-invalid':v$.amount.$error,'is-valid':!v$.amount.$invalid}"
                                                               :placeholder="$t('global.Amount')"
                                                        >
                                                        <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                        <div class="invalid-feedback">
                                                            <span v-if="v$.amount.decimal.$invalid"> هذا الحقل يجب ان يكون رقم<br /> </span>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

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
import {computed, onMounted, reactive,toRefs,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required,minLength,decimal,maxLength,integer,email} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "createClient",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);
        let areas = ref([]);
        let selling_methods = ref([]);
        let provinces = ref([]);
        let knowledgeWays = ref([]);

        //start design
        let addClient =  reactive({
            data:{
                name : '',
                email : '',
                phone : '',
                address : '',
                province_id : null,
                area_id : null,
                knowledge_way_id: null,
                // selling_method_id : null,
                amount: 0
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                email: {
                    email,
                    required
                },
                phone: {
                    required,
                    integer
                },
                address: {
                    required
                },
                amount:{decimal},
                province_id:{required,integer},
                area_id:{required,integer},
                knowledge_way_id:{required,integer}
                // selling_method_id:{required,integer},
            }
        });

        let getStore= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/create`)
                .then((res) => {
                    let l = res.data.data;
                    provinces.value = l.provinces;
                    knowledgeWays.value = l.knowledgeWays;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        };

        let getSellingMethods= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/activeSellingMethod`)
                .then((res) => {
                    let l = res.data.data;
                    selling_methods.value = l.sellingMethods;
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
            getSellingMethods();
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

        const v$ = useVuelidate(rules,addClient.data);

        return {loading,...toRefs(addClient),areas,selling_methods,provinces,knowledgeWays,getAreas,v$};
    },
    methods: {
        storeClient(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/client`,this.data)
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
                        console.log(err.response);
                        // this.errors = err.response;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.name = '';
            this.data.email = '';
            this.data.phone = '';
            this.data.address = '';
            this.data.province_id = null;
            this.data.area_id = null;
            this.data.knowledge_way_id = null;
            this.data.amount = 0;
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

.card{
    position: relative;
}

.sec-body{
    border: 3px solid #000;
    border-radius: 20px;
    padding: 10px;
}
.sec-head{
    background-color: #103C67;
    color: #FFF;
    border-radius: 11px;
    padding: 5px;
    text-align: center;
    margin-bottom: 8px;
    margin-top: 10px;
}
.sec-body:hover .sec-head{
    border: 3px solid #000;
    padding: 2px;
    border-radius: 11px;
    background-color: #ffff;
}
.sec-body:hover .sec-head h3{
    color: #000;
}
.sec-head h3{
    font-weight: 700;
    color: #FFF;
}
</style>
