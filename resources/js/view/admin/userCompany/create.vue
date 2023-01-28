<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title"> {{$t('sidebar.userCompany')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexUserCompany'}"> {{$t('sidebar.userCompany')}}</router-link></li>
                            <li class="breadcrumb-item active">اضافه </li>
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
                                    :to="{name: 'indexUserCompany'}"
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
                                    <div class="alert alert-danger text-center" v-if="errors['job']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['nameCompany']">{{ errors['nameCompany'][0] }}<br /> </div>

                                    <form @submit.prevent="storeClient" class="needs-validation">
                                        <div class="form-row row">

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

                                            <div class="col-md-6 mb-3">
                                                <label>اسم الوظيفه </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.job.$model"
                                                       placeholder="اسم الوظيفه"
                                                       :class="{'is-invalid':v$.job.$error,'is-valid':!v$.job.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.job.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.job.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.job.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.job.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.job.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>اسم الشركه </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.nameCompany.$model"
                                                       placeholder="اسم الوظيفه"
                                                       :class="{'is-invalid':v$.nameCompany.$error,'is-valid':!v$.nameCompany.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.nameCompany.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.nameCompany.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.nameCompany.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.nameCompany.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.nameCompany.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>

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
                                                <label for="validationCustom04">رقم تليفون اخر </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.phone_second.$model"
                                                       id="validationCustom05"
                                                       placeholder="رقم تليفون اخر"
                                                       :class="{'is-invalid':v$.phone_second.$error,'is-valid':!v$.phone_second.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom011">لنك الفيس بوك </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.facebook.$model"
                                                       id="validationCustom011"
                                                       placeholder="لنك الفيس بوك "
                                                       :class="{'is-invalid':v$.facebook.$error,'is-valid':!v$.facebook.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom012">لنك linkedin </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.linkedin.$model"
                                                       id="validationCustom012"
                                                       placeholder="لنك linkedin "
                                                       :class="{'is-invalid':v$.linkedin.$error,'is-valid':!v$.linkedin.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom013">لنك website</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.website.$model"
                                                       id="validationCustom013"
                                                       placeholder="لنك website"
                                                       :class="{'is-invalid':v$.website.$error,'is-valid':!v$.website.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom016">لنك الواتس </label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.whatsapp.$model"
                                                       id="validationCustom016"
                                                       placeholder="لنك الواتس "
                                                       :class="{'is-invalid':v$.whatsapp.$error,'is-valid':!v$.whatsapp.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
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

                                            <div class="col-md-6 mb-3 mt-5" style="display: none">
                                                <div class="sec-body">
                                                    <div class="col-md-12 mb-12 sec-head">
                                                        <h3>
                                                            {{ $t('global.TheBalanceOfTheFirstDuration') }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!---->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">رقم السجل التجاري</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.commercial_record.$model"
                                                    id="validationCustom04"
                                                    placeholder="رقم السجل التجاري"
                                                    :class="{'is-invalid':v$.commercial_record.$error,'is-valid':!v$.commercial_record.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.commercial_record.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.commercial_record.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom06">رقم البطاقة الضريبية</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.tax_card.$model"
                                                    id="validationCustom06"
                                                    placeholder="رقم البطاقة الضريبية"
                                                    :class="{'is-invalid':v$.tax_card.$error,'is-valid':!v$.tax_card.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.tax_card.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.tax_card.maxLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>

                                            <!--start images-->
                                            <div class="col-md-6 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage"
                                                        type="file"
                                                        @change="preview1"
                                                        id="mediaPackage1"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">صورة السجل التجاري</span>
                                                <p class="num-of-files">{{numberOfImage1 ? numberOfImage1 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images1" v-show="data.commercialRegister && numberOfImage1"></div>
                                                <div class="container-images" v-show="!numberOfImage1">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>

                                            <div class="col-md-6 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        Choose files
                                                        <i class="fas fa-cloud-upload-alt ml-3" aria-hidden="true"></i>
                                                    </span>
                                                    <input
                                                        name="mediaPackage"
                                                        type="file"
                                                        @change="preview2"
                                                        id="mediaPackage2"
                                                        accept="image/png,jepg,jpg"
                                                    >
                                                </div>
                                                <span class="text-danger text-center">صوره البطاقه الضربيه</span>
                                                <p class="num-of-files">{{numberOfImage2 ? numberOfImage2 + ' Files Selected' : 'No Files Chosen' }}</p>
                                                <div class="container-images" id="container-images2" v-show="data.taxCard && numberOfImage2"></div>
                                                <div class="container-images" v-show="!numberOfImage2">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/admin/img/company/img-1.png`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--end images-->
                                            <!---->

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
import {required,minLength,decimal,maxLength,integer,email,url} from '@vuelidate/validators';
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
        let numberOfImage1 = ref(0);
        let numberOfImage2 = ref(0);
        let areas = ref([]);
        let selling_methods = ref([]);
        let provinces = ref([]);

        //start design
        let addClient =  reactive({
            data:{
                name : '',
                email : '',
                phone : '',
                nameCompany:'',
                job: '',
                facebook:'',
                linkedin:'',
                website:'',
                whatsapp:'',
                phone_second:'',
                address : '',
                province_id : null,
                area_id : null,
                commercial_record : '',
                tax_card : '',
                commercialRegister: {},
                taxCard: {},
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                nameCompany: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                job:{
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
                facebook:{
                    url
                },
                linkedin:{
                    url
                },
                website:{
                    url
                },
                whatsapp:{
                    url
                },
                phone_second:{},
                address: {
                    required
                },
                province_id: {
                    required,
                    integer
                },
                area_id: {
                    required,
                    integer
                },
                commercial_record: {
                    maxLength:maxLength(100),
                },
                tax_card: {
                    maxLength:maxLength(100),
                },
            }
        });

        let getStore= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/create`)
                .then((res) => {
                    let l = res.data.data;
                    provinces.value = l.provinces;
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

        let preview1 = (e) => {

            let containerImages = document.querySelector('#container-images1');
            if(numberOfImage1.value){
                containerImages.innerHTML = '';
            }
            addClient.data.commercialRegister = {};

            numberOfImage1.value = e.target.files.length;

            addClient.data.commercialRegister = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addClient.data.commercialRegister.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addClient.data.commercialRegister);

        };

        let preview2 = (e) => {

            let containerImages = document.querySelector('#container-images2');
            if(numberOfImage2.value){
                containerImages.innerHTML = '';
            }
            addClient.data.taxCard = {};

            numberOfImage2.value = e.target.files.length;

            addClient.data.taxCard = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addClient.data.taxCard.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addClient.data.taxCard);

        };

        const v$ = useVuelidate(rules,addClient.data);

        return {loading,...toRefs(addClient),areas,selling_methods,provinces,getAreas,v$,preview1,preview2,numberOfImage1,numberOfImage2};
    },
    methods: {
        storeClient(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append('name',this.data.name);
                formData.append('nameCompany',this.data.nameCompany);
                formData.append('job',this.data.job);
                formData.append('email',this.data.email);
                formData.append('phone',this.data.phone);
                formData.append('facebook',this.data.facebook);
                formData.append('linkedin',this.data.linkedin);
                formData.append('website',this.data.website);
                formData.append('whatsapp',this.data.whatsapp);
                formData.append('phone_second',this.data.phone_second);
                formData.append('address',this.data.address);
                formData.append('province_id',this.data.province_id);
                formData.append('area_id',this.data.area_id);
                formData.append('commercial_record',this.data.commercial_record);
                formData.append('tax_card',this.data.tax_card);
                formData.append('commercialRegister',this.data.commercialRegister);
                formData.append('taxCard',this.data.taxCard);

                adminApi.post(`/v1/dashboard/userCompany`,formData)
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
        resetForm()
        {
            this.data.name = '';
            this.data.email = '';
            this.data.phone = '';
            this.data.nameCompany = '';
            this.data.job= '';
            this.data.facebook='';
            this.data.linkedin='';
            this.data.website='';
            this.data.whatsapp='';
            this.data.commercial_record = '';
            this.data.area_id = null;
            this.data.province_id = null;
            this.data.address = '';
            this.data.phone_second='';
            this.data.tax_card = '';
            document.querySelector('#mediaPackage1').value = '';
            document.querySelector('#mediaPackage2').value = '';
            document.querySelector('#container-images1').innerHTML = '';
            document.querySelector('#container-images2').innerHTML = '';
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
