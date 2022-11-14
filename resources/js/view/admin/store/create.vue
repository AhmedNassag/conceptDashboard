<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('store.store') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexStore'}">{{ $t('store.store') }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t('store.CreateStore') }}</li>
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
                                    :to="{name: 'indexStore'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{ $t('global.back') }}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="storeSupplier" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم المخزن</label>
                                                <input
                                                   type="text" class="form-control"
                                                   v-model.trim="v$.name.$model"
                                                   id="validationCustom01"
                                                   placeholder="اسم المخزن"
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
                                                <label for="validationCustom03">{{ $t('global.Phone') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       id="validationCustom03"
                                                       :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">{{ $t('global.Email') }}</label>
                                                <input type="email" class="form-control"
                                                       v-model.trim="v$.email.$model"
                                                       id="validationCustom04"
                                                       :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.email.$invalid"> يجب ان يكون بؤيد الكترونى  <br /></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom05">{{ $t('global.Address') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.address.$model"
                                                       id="validationCustom05"
                                                       :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback"></div>
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
                                                <label for="validationCustom07">{{ $t('global.Location') }}</label>
                                                <input type="text" class="form-control"
                                                       v-model.trim="v$.location.$model"
                                                       id="validationCustom07"
                                                       :placeholder="$t('global.Location')"
                                                       :class="{'is-invalid':v$.location.$error,'is-valid':!v$.location.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>المناطق التابعة للمخزن</label>
                                                <select
                                                    name="type"
                                                    class="form-control"
                                                    multiple
                                                    v-model="v$.areas.$model"
                                                    :class="{'is-invalid':v$.areas.$error,'is-valid':!v$.areas.$invalid}"
                                                >
                                                    <option v-for="storeArea in storeAreas" :value="storeArea.id" >
                                                        {{ storeArea.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.areas.required.$invalid"> هذا الحقل مطلوب<br /> </span>
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
import {required,minLength,email,maxLength,integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "createDepartment",
    data(){
        return {
            errors:{}
        }
    },
    setup(){
        let loading = ref(false);
        const {t} = useI18n({});
        let storeAreas = ref([]);
        let areas = ref([]);
        let provinces = ref([]);

        //start design
        let addStore =  reactive({
            data:{
                name : '',
                email : '',
                phone : '',
                province_id : null,
                area_id : null,
                address : '',
                location: '',
                areas:[]
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                email: {email},
                address: {},
                location: {},
                phone: {
                    integer,
                    required
                },
                province_id:{required,integer},
                area_id:{required,integer},
                areas: {
                    required
                }
            }
        });


        const v$ = useVuelidate(rules,addStore.data);

        let getStore= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/create`)
                .then((res) => {
                    let l = res.data.data;
                    storeAreas.value = l.areas;
                    provinces.value = l.provinces;
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


        return {t,getAreas,loading,...toRefs(addStore),v$,areas,provinces,storeAreas,getStore};
    },
    methods: {
        storeSupplier(){

            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/store`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.AddedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        this.resetForm();
                        this.getStore();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        // this.errors = err.response.data.errors;
                        console.log(err.response);
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.name = '';
            this.data.location = '';
            this.data.address = '';
            this.data.phone = '';
            this.data.email = '';
            this.data.area_id = '';
            this.data.province_id = '';
            this.data.areas = [];
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

.custom-textarea {
    height: 150px;
}
</style>
