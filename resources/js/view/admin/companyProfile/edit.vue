<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">ملف الشركة</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexCompanyProfile'}">ملف الشركة</router-link></li>
                            <li class="breadcrumb-item active">تعديل ملف الشركة</li>
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
                                    :to="{name: 'indexCompanyProfile'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العودة للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">{{ errors['address'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['tax_record']">{{ errors['tax_record'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['commercial_record']">{{ errors['commercial_record'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['file']">{{ errors['file'][0] }}<br /> </div>

                                    <form @submit.prevent="editCompanyProfile" class="needs-validation">
                                        <div class="form-row row">

                                            <!--start name-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم الشركة</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       placeholder="اسم الشركة"
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

                                            <!--start address-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">عنوان الشركة</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.address.$model"
                                                    id="validationCustom02"
                                                    placeholder="عنوان الشركة"
                                                    :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.address.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.address.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.address.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.address.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end address-->

                                            <!--start phone-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">رقم التليفون</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.phone.$model"
                                                    id="validationCustom03"
                                                    placeholder="رقم التليفون"
                                                    :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>
                                            <!--end phone-->

                                            <!--start tax_card-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">رقم البطاقة الضريبية</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.tax_card.$model"
                                                    id="validationCustom04"
                                                    placeholder="رقم البطاقة الضريبية"
                                                    :class="{'is-invalid':v$.tax_card.$error,'is-valid':!v$.tax_card.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.tax_card.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.tax_card.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.tax_card.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.tax_card.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.tax_card.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end tax_card-->

                                            <!--start commercial_record-->
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">رقم السجل التجارى</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.commercial_record.$model"
                                                    id="validationCustom05"
                                                    placeholder="رقم السجل التجارى"
                                                    :class="{'is-invalid':v$.commercial_record.$error,'is-valid':!v$.commercial_record.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.commercial_record.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.commercial_record.maxLength.$invalid"> يجب ان يكون علي الاقل {{ v$.commercial_record.minLength.$params.min }} حرف  <br /></span>
                                                    <span v-if="v$.commercial_record.minLength.$invalid">يجب ان يكون علي اكثر  {{ v$.commercial_record.maxLength.$params.max }} حرف</span>
                                                </div>
                                            </div>
                                            <!--end commercial_record-->

                                            <!--start image-->
                                            <div class="col-md-12 row flex-fill">
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
                                                <div class="container-images" id="container-images" v-show="data.file && numberOfImage"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption v-if="image">
                                                            <img :src="`/upload/companyProfile/${image}`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <!--end image-->

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
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
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
        let image = ref('');

        let getCompanyProfile = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/companyProfile/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;

                    addCompanyProfile.data.name = l.companyProfile.name;
                    addCompanyProfile.data.address = l.companyProfile.address;
                    addCompanyProfile.data.phone = l.companyProfile.phone;
                    addCompanyProfile.data.tax_card = l.companyProfile.tax_card;
                    addCompanyProfile.data.commercial_record = l.companyProfile.commercial_record;
                    image.value = l.companyProfile.media.file_name;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getCompanyProfile();
        });

        //start design
        let addCompanyProfile =  reactive({
            data:{
                name : '',
                address : '',
                phone : '',
                tax_card : '',
                commercial_record : '',
                file : {}
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                address: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                phone: {
                    required,
                },
                tax_card: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                commercial_record: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
            }
        });

        const v$ = useVuelidate(rules,addCompanyProfile.data);

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }
            addCompanyProfile.data.file = {};

            numberOfImage.value = e.target.files.length;

            addCompanyProfile.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addCompanyProfile.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addCompanyProfile.data.file);

        };

        const numberOfImage = ref(0);

        return {id,loading,...toRefs(addCompanyProfile),v$,preview,numberOfImage,image};
    },
    methods: {
        editCompanyProfile(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                let formData = new FormData();
                formData.append('name',this.data.name);
                formData.append('address',this.data.address);
                formData.append('phone',this.data.phone);
                formData.append('tax_card',this.data.tax_card);
                formData.append('commercial_record',this.data.commercial_record);
                formData.append('file',this.data.file);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/companyProfile/${this.id}`,formData)
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
                        document.querySelector('#mediaPackage').value = '';

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
</style>
