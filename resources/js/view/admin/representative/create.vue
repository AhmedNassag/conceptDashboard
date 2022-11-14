<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"/>
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.representative') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexRepresentative'}">
                                    {{ $t('global.representative') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Add') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <!-- Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <loader v-if="loading"/>
                        <div class="card-body">
                            <div class="card-header pt-0 mb-4">
                                <router-link
                                    :to="{name: 'indexRepresentative'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t('global.back') }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['name']">
                                        {{ errors['name'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['email']">
                                        {{ errors['email'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['password']">
                                        {{ errors['password'][0] }}<br/></div>
                                    <div class="alert alert-danger text-center" v-if="errors['confirmtion']">
                                        {{ errors['confirmtion'][0] }}<br/></div>
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
                                    <form @submit.prevent="storeEmployee" class="needs-validation">
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

                                            <div class="col-md-6">
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


                                            <div class="col-md-6">
                                                <label for="validationCustom02">{{ $t('global.Password') }}</label>
                                                <input type="password" v-model.trim="v$.password.$model"
                                                       id="validationCustom09"
                                                       :class="['form-control',{'is-invalid':v$.password.$error,'is-valid':!v$.password.$invalid}]"
                                                       :placeholder="$t('global.Password')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                                <div class="invalid-feedback">
                                                        <span
                                                            v-if="v$.password.required.$invalid">{{$t('global.PasswordIsRequired')}}<br/> </span>
                                                    <span v-if="v$.password.alphaNum.$invalid">{{$t('global.MustBeLettersOrNumbers')}} <br/></span>
                                                    <span v-if="v$.password.minLength.$invalid">{{$t('global.PasswordIsMustHaveAtLeast')}} {{
                                                            v$.password.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span v-if="v$.password.maxLength.$invalid">{{$t('global.PasswordIsMustHaveAtMost')}} {{
                                                            v$.password.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{ $t('global.confirm') }}</label>
                                                <input type="password" v-model.trim="v$.confirmtion.$model"
                                                       id="validationCustom10"
                                                       :class="['form-control',{'is-invalid':v$.confirmtion.$error,'is-valid':!v$.confirmtion.$invalid}]"
                                                       :placeholder="$t('global.confirm')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>

                                                <div class="invalid-feedback">
                                                    <span v-if="v$.confirmtion.required.$invalid">{{$t('global.ConfirmIsRequired')}}<br/> </span>
                                                    <span v-if="v$.confirmtion.sameAs.$invalid">{{$t('global.ConfirmationMustMatchPassword')}} <br/></span>
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
                                                <span class="text-danger text-center" v-if="requiredn">{{$t('global.ImagesIsMustHaveAtLeast1Photos')}}<br/></span>
                                                <div class="container-images" id="container-images"
                                                     v-show="numberOfImage"></div>
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
import {computed, onMounted, reactive, toRefs, inject, ref, watch} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, between, maxLength, alpha, integer, email, alphaNum, sameAs} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import {notify} from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "create",
    data() {
        return {
            errors: {}
        }
    },
    setup() {
        const {t} = useI18n({});
        let loading = ref(false);
        let areas = ref([]);

        let getMainAreaViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/representative/create`)
                .then((res) => {
                    let l = res.data.data;
                    areas.value= l.areas;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainAreaViews();
        });

        let addEmployee = reactive({
            data: {
                name: '',
                email: '',
                password: '',
                confirmtion: '',
                area_id: [],
                phone: '',
                address: '',
                National_ID: '',
                birth_date: '',
                hiring_date: '',
                salary: '',
                file: {}
            }
        });

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
                password: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(16),
                    alphaNum
                }
                ,
                confirmtion: {
                    required,
                    sameAs: sameAs(addEmployee.data.password)
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
                },
                file:{
                    required
                }
            }
        });
        const numberOfImage = ref(0);
        let empty = () => {
            numberOfImage.value = 0;
            let clearInput = document.querySelector('#mediaPackage').value = '';
            requiredn.value = false;
        }

        let preview = (e) => {

            let containerImages = document.querySelector('#container-images');
            containerImages.innerHTML = '';

            addEmployee.data.file = {};
            numberOfImage.value = e.target.files.length;

            addEmployee.data.file = e.target.files[0];

            let reader = new FileReader();
            let figure = document.createElement('figure');
            let figcap = document.createElement('figcaption');

            figcap.innerText = addEmployee.data.file.name;
            figure.appendChild(figcap);

            reader.onload = () => {
                let img = document.createElement('img');
                img.setAttribute('src', reader.result);
                figure.insertBefore(img, figcap);
            }

            containerImages.appendChild(figure);
            reader.readAsDataURL(addEmployee.data.file);


        }
        // validation image
        const requiredn = ref(false);

        watch(numberOfImage, (count, prevCount) => {
            if(!count){
                requiredn.value = true;
            }else {
                requiredn.value = false;
            }
        });


        const v$ = useVuelidate(rules, addEmployee.data);


        return {areas,preview, t, loading, ...toRefs(addEmployee), v$,requiredn,numberOfImage,empty};
    },
    methods: {
        storeEmployee() {
            this.v$.$validate();

            if (!this.v$.$error) {

                this.loading = true;
                this.errors = {};
                let formData = new FormData();
                formData.append('name',this.data.name);
                formData.append('email',this.data.email);
                formData.append('password',this.data.password);
                formData.append('confirmtion',this.data.confirmtion);
                formData.append('area_id',this.data.area_id);
                formData.append('phone',this.data.phone);
                formData.append('address',this.data.address);
                formData.append('National_ID',this.data.National_ID);
                formData.append('birth_date',this.data.birth_date);
                formData.append('hiring_date',this.data.hiring_date);
                formData.append('salary',this.data.salary);
                formData.append('file',this.data.file);


                adminApi.post(`/v1/dashboard/representative`, formData)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });
                        this.empty();
                        this.resetForm();
                        this.$nextTick(() => {
                            this.v$.$reset()
                        });
                    })
                    .catch((err) => {
                        console.log(err.response)
                        // this.errors = err.response.data.errors;

                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }else{
                if(!this.numberOfImage){
                    this.requiredn = true;
                }
            }
        },
        resetForm() {
            this.data.name= '';
            this.data.email= '';
            this.data.password= '';
            this.data.confirmtion= '';
            this.data.phone= '';
            this.data.area_id= [];
            this.data.address= '';
            this.data.National_ID= '';
            this.data.birth_date= '';
            this.data.hiring_date= '';
            this.data.salary= '';
            this.data.file= {}
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
