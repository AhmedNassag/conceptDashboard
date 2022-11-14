<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.representative')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexRepresentative'}">{{$t('global.representative')}}</router-link></li>
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
                                    :to="{name: 'indexRepresentative'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">
                                        {{ errors['name'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['email']">
                                        {{ errors['email'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">
                                        {{ errors['phone'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">
                                        {{ errors['address'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['National_ID']">
                                        {{ errors['National_ID'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['birth_date']">
                                        {{ errors['birth_date'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['hiring_date']">
                                        {{ errors['hiring_date'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['salary']">
                                        {{ errors['salary'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['file']">
                                        {{ errors['file'][0] }}<br/></div>
                                    <form @submit.prevent="editEmployee" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{ $t('global.Name') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.name.$model"
                                                       id="validationCustom01"
                                                       :placeholder="$t('global.Name')"
                                                       :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.name.required.$invalid">{{ $t('global.NameIsRequired') }}<br/> </span>
                                                    <span
                                                        v-if="v$.name.minLength.$invalid">{{ $t('global.NameIsMustHaveAtLeast') }} {{
                                                            v$.name.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.name.maxLength.$invalid">{{ $t('global.NameIsMustHaveAtMost') }} {{
                                                            v$.name.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.NationalID') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.National_ID.$model"
                                                       id="validationCustom02"
                                                       :class="{'is-invalid':v$.National_ID.$error,'is-valid':!v$.National_ID.$invalid}"
                                                       :placeholder="$t('global.NationalID')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.National_ID.required.$invalid">{{ $t('global.NationalIdIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.National_ID.minLength.$invalid">{{ $t('global.NationalIdIsMustHaveAtLeast') }} {{
                                                            v$.National_ID.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.Phone') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       id="validationCustom03"
                                                       :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                       :placeholder="$t('global.Phone')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.phone.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.phone.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{
                                                            v$.phone.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.phone.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{
                                                            v$.phone.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>



                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.Address') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       id="validationCustom05"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                       :placeholder="$t('global.Address')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.address.required.$invalid">{{ $t('global.AddressIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.address.minLength.$invalid">{{ $t('global.AddressIsMustHaveAtLeast') }} {{
                                                            v$.address.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.address.maxLength.$invalid">{{ $t('global.AddressIsMustHaveAtMost') }} {{
                                                            v$.address.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.HiringDate') }}</label>
                                                <input type="date"
                                                       class="form-control"
                                                       v-model.trim="v$.hiring_date.$model"
                                                       id="validationCustom08"
                                                       :class="{'is-invalid':v$.hiring_date.$error,'is-valid':!v$.hiring_date.$invalid}"
                                                       :placeholder="$t('global.HiringDate')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.hiring_date.required.$invalid">{{ $t('global.HiringDateIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.BirthDate') }}</label>
                                                <input type="date"
                                                       class="form-control"
                                                       v-model.trim="v$.birth_date.$model"
                                                       id="validationCustom07"
                                                       :class="{'is-invalid':v$.birth_date.$error,'is-valid':!v$.birth_date.$invalid}"
                                                       :placeholder="$t('global.BirthDate')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.birth_date.required.$invalid">{{ $t('global.BirthDateIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.Salary') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.trim="v$.salary.$model"
                                                       id="validationCustom06"
                                                       :class="{'is-invalid':v$.salary.$error,'is-valid':!v$.salary.$invalid}"
                                                       :placeholder="$t('global.Salary')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.salary.required.$invalid">{{ $t('global.SalaryIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="validationCustom02">{{ $t('global.Email') }}</label>
                                                    <input type="email"
                                                           class="form-control"
                                                           v-model.trim="v$.email.$model"
                                                           id="validationCustom04"
                                                           :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                           :placeholder="$t('global.Email')"
                                                    >
                                                    <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                    <div class="invalid-feedback">
                                                        <span
                                                            v-if="v$.email.required.$invalid">{{ $t('global.EmailIsRequired') }} <br/></span>
                                                        <span
                                                            v-if="v$.email.email.$invalid">{{ $t('global.ThisFieldMastBeEmail') }}  <br/></span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="WebsitePages">{{$t('global.choseArea')}}</label>
                                                    <select
                                                        multiple
                                                        class="form-control coustom-select"
                                                        id="WebsitePages"
                                                        v-model="v$.area_id.$model"
                                                        :class="{'is-invalid':v$.area_id.$error,'is-valid':!v$.area_id.$invalid}"
                                                        v-if="areas"
                                                    >
                                                        <option v-for="area in areas" :value="area.id" :kay="area.id">
                                                            {{ area.name}}
                                                        </option>

                                                    </select>
                                                    <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                    <div class="invalid-feedback">
                                                        <span v-if="v$.area_id.required.$invalid">{{$t('global.AreaIsRequired')}}</span>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-md-12 mb-12 row flex-fill">
                                                <div class="btn btn-outline-primary waves-effect">
                                                    <span>
                                                        {{$t('global.ChooseImage')}}
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
                                                <p class="num-of-files">{{
                                                        numberOfImage ? numberOfImage + $t('global.FilesSelected') : $t('global.NoFilesChosen')
                                                    }}</p>
                                                <div class="container-images" v-show="numberOfImage" id="container-images"></div>
                                                <div class="container-images" v-show="!numberOfImage">
                                                    <figure>
                                                        <figcaption>
                                                            <img :src="`/upload/representative_profile/${image}`">
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn btn-primary" type="submit">{{ $t('global.Submit') }}</button>
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
import {computed, onBeforeMount, reactive,toRefs,inject,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, email, alphaNum, sameAs} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editEmployee",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        const {t} = useI18n({});
        let areas = ref([]);
        let user_id = ref('');
        let loading = ref(false);

        const numberOfImage = ref(0);
        const image = ref('');
        const imageUpload = ref({});
        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            if(numberOfImage.value){
                containerImages.innerHTML = '';
            }

            addEmployee.data.file = {}
            numberOfImage.value = e.target.files.length;

            addEmployee.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addEmployee.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src',reader.result);
                figure.insertBefore(img,figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addEmployee.data.file);

        };

        let getMainEmployeeViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/representative/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    areas.value = l.areas;
                    user_id.value = l.representative.user_id;
                    addEmployee.data.name = l.representative.user.name;
                    addEmployee.data.email = l.representative.user.email;
                    addEmployee.data.phone = l.representative.user.phone;
                    addEmployee.data.address = l.representative.address;
                    addEmployee.data.National_ID = l.representative.National_ID;
                    addEmployee.data.birth_date = l.representative.birth_date;
                    addEmployee.data.hiring_date = l.representative.hiring_date;
                    addEmployee.data.salary = l.representative.salary;
                    image.value = l.media;

                    l.representative.areas.forEach(el =>{
                        addEmployee.data.area_id .push(el.id);
                    })
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onBeforeMount(() => {
            getMainEmployeeViews();
        });

        let addEmployee =  reactive({
            data:{
                name: '',
                email: '',
                area_id: [],
                phone: '',
                address: '',
                National_ID: '',
                birth_date: '',
                hiring_date: '',
                salary: '',
                file: {}
            }
        })

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                    required
                },
                email: {
                    email,
                    required
                },
                phone: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                area_id: {
                    required
                },
                address:{
                    minLength: minLength(3),
                    maxLength: maxLength(100),
                    required
                },
                National_ID:{
                    minLength: minLength(8),
                    required
                },
                birth_date:{
                    required
                },
                hiring_date:{
                    required
                },
                salary:{
                    required
                }
            }
        });


        const v$ = useVuelidate(rules,addEmployee.data);


        return {t,user_id,preview,imageUpload,image,numberOfImage,areas,loading,...toRefs(addEmployee),v$};
    },
    methods: {
        editEmployee(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append('name',this.data.name);
                formData.append('email',this.data.email);
                formData.append('area_id',this.data.area_id);
                formData.append('phone',this.data.phone);
                formData.append('address',this.data.address);
                formData.append('National_ID',this.data.National_ID);
                formData.append('birth_date',this.data.birth_date);
                formData.append('hiring_date',this.data.hiring_date);
                formData.append('salary',this.data.salary);
                formData.append('file',this.data.file);
                formData.append('_method','PUT');

                adminApi.post(`/v1/dashboard/representative/${this.id}`,formData)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                    })
                    .catch((err) => {
                        // this.errors = err.response.data.errors;
                        console.log(err.response)
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

.card {
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

.num-of-files {
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
