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
                            <li class="breadcrumb-item active">تعديل العميل</li>
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
                                    <div class="alert alert-danger text-center" v-if="errors['trade_name']">{{ errors['trade_name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['email']">{{ errors['email'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">{{ errors['address'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['location']">{{ errors['location'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['selling_method_id']">{{ errors['selling_method_id'][0] }}<br /> </div>

                                    <form @submit.prevent="editClient" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
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

                                            <div class="col-md-6 mb-3">
                                                <label>اسم التجارى</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.trade_name.$model"
                                                       placeholder="اسم التجارى"
                                                       :class="{'is-invalid':v$.trade_name.$error,'is-valid':!v$.trade_name.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.trade_name.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.trade_name.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.trade_name.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.trade_name.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.trade_name.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">البريد الالكتؤونى </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.email.$model"
                                                       id="validationCustom02"
                                                       placeholder="البريد الالكترونى"
                                                       :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.email.$invalid"> يجب ان يكون الاميل  <br /></span>
                                                </div>
                                            </div>

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

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">الموقع</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.location.$model"
                                                       id="validationCustom05"
                                                       placeholder="الموقع"
                                                       :class="{'is-invalid':v$.location.$error,'is-valid':!v$.location.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label >{{ $t('global.sellingMethod') }}</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    v-model="v$.selling_method_id.$model"
                                                    :class="{'is-invalid':v$.selling_method_id.$error,'is-valid':!v$.selling_method_id.$invalid}"
                                                >
                                                    <option value="">---</option>
                                                    <option
                                                        v-for="selling_method in selling_methods"
                                                        :value=" selling_method.id"
                                                        :key=" selling_method.id"
                                                    >{{ selling_method.name }}</option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.selling_method_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.selling_method_id.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3 mt-5">
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
import {computed, onMounted, reactive,toRefs,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, integer, email, decimal} from '@vuelidate/validators';
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
        let areas = ref([]);
        let provinces = ref([]);
        let selling_methods = ref([]);

        let getClient = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/client/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    provinces.value = l.provinces;
                    addClient.data.name = l.user.name;
                    addClient.data.trade_name = l.user.client.trade_name;
                    addClient.data.province_id = l.user.client.province_id;
                    addClient.data.area_id = l.user.client.area_id;
                    addClient.data.phone = l.user.phone;
                    addClient.data.email = l.user.email;
                    addClient.data.address = l.user.client.address;
                    addClient.data.location = l.user.client.location;
                    addClient.data.selling_method_id = l.user.client.selling_method_id;
                    addClient.data.amount = l.user.client_accounts[0].amount;
                    getAreas(l.user.client.province_id);
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

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
            getClient();
            getSellingMethods();
        });

        //start design
        let addClient =  reactive({
            data:{
                name : '',
                trade_name : '',
                province_id : null,
                area_id : null,
                email : '',
                phone : '',
                address : '',
                location : '',
                selling_method_id : null,
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
                trade_name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                email: {
                    email
                },
                phone: {
                    required,
                    integer
                },
                address: {
                    required
                },
                amount:{decimal},
                location: {},
                province_id:{required,integer},
                area_id:{required,integer},
                selling_method_id:{required,integer},
            }
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

        return {id,loading,...toRefs(addClient),v$,areas,selling_methods,provinces,getAreas};
    },
    methods: {
        editClient(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/client/${this.id}`,this.data)
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

.card{
    position: relative;
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
