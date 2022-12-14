<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">

        <div class="content container-fluid">

            <notifications :position="this.$i18n.locale == 'ar'? 'top left': 'top right'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.Leads')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexLeadSalesHome', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Categories') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexLead', params: {lang: locale || 'ar',id:idTarget}}">{{$t('global.Leads')}}</router-link></li>
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
                                    :to="{name: 'indexLead', params: {lang: locale || 'ar',id:idTarget}}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">

                                    <div class="alert alert-danger text-center" v-if="errors['name']">{{ errors['name'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['email']">{{ errors['email'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['address']">{{ errors['address'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['province_id']">{{ errors['province_id'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['area_id']">{{ errors['area_id'][0] }}<br/> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['amount']">{{ errors['amount'][0] }}<br/> </div>

                                    <form @submit.prevent="editLead" class="needs-validation">
                                        <div class="form-row row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">{{$t('global.Name')}}</label>
                                                <input type="text" class="form-control"
                                                   v-model.trim="v$.name.$model"
                                                   id="validationCustom01"
                                                   :placeholder="$t('global.Name')"
                                                   :class="{'is-invalid':v$.name.$error,'is-valid':!v$.name.$invalid}"
                                                   disabled
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.name.required.$invalid">{{$t('global.NameIsRequired')}}<br /> </span>
                                                    <span v-if="v$.name.minLength.$invalid">{{$t('global.NameIsMustHaveAtLeast')}} {{ v$.name.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.name.maxLength.$invalid">{{$t('global.NameIsMustHaveAtMost')}} {{ v$.name.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom02">{{$t('global.Address')}}</label>
                                                <input type="text"
                                                   class="form-control"
                                                   v-model.trim="v$.address.$model"
                                                   id="validationCustom02"
                                                   :class="{'is-invalid':v$.address.$error,'is-valid':!v$.address.$invalid}"
                                                   :placeholder="$t('global.Address')"
                                                   disabled
                                                >
                                                <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.address.minLength.$invalid">{{$t('global.AddressIsMustHaveAtLeast')}} {{ v$.address.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
                                                    <span v-if="v$.address.maxLength.$invalid">{{$t('global.AddressIsMustHaveAtMost')}} {{ v$.address.maxLength.$params.max }} {{$t('global.Letters')}} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom03">{{ $t('global.Phone') }}</label>
                                                <input type="text"
                                                   class="form-control"
                                                   v-model.trim="v$.phone.$model"
                                                   id="validationCustom03"
                                                   :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                   :placeholder="$t('global.Phone')"
                                                   disabled
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span v-if="v$.phone.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{v$.phone.minLength.$params.min }} {{ $t('global.Letters') }} <br/></span>
                                                    <span v-if="v$.phone.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{v$.phone.maxLength.$params.max }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom04">{{ $t('global.Email') }}</label>
                                                <input type="email"
                                                   class="form-control"
                                                   v-model.trim="v$.email.$model"
                                                   id="validationCustom04"
                                                   :class="{'is-invalid':v$.email.$error,'is-valid':!v$.email.$invalid}"
                                                   :placeholder="$t('global.Email')"
                                                   disabled
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.email.email.$invalid">{{ $t('global.ThisFieldMastBeEmail') }}<br/></span>
                                                </div>
                                            </div>


                                            <div class="col-md-6 mb-3">
                                                <label>المحافظة</label>
                                                <select @change="getAreas(v$.province_id.$model)"
                                                    name="type"
                                                    class="form-select"
                                                    v-model="v$.province_id.$model"
                                                    :class="{'is-invalid':v$.province_id.$error,'is-valid':!v$.province_id.$invalid}"
                                                >
                                                    <option v-for="province in provinces" :value=" province.id" :key=" province.id">
                                                        {{ province.name }}
                                                    </option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.province_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ $t('global.choseArea') }}</label>
                                                <select name="type"
                                                    class="form-select"
                                                    v-model="v$.area_id.$model"
                                                    :class="{'is-invalid':v$.area_id.$error,'is-valid':!v$.area_id.$invalid}"
                                                >
                                                    <option v-for="area in areas" :value=" area.id" :key=" area.id">{{ area.name }}</option>
                                                </select>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.area_id.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.area_id.integer.$invalid"> يجب ان يكون رقم  <br /></span>
                                                </div>
                                            </div>


                                        </div>
                                        <button class="btn btn-primary" type="submit">{{$t('global.Submit')}}</button>
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
import {required, minLength, between, maxLength, alpha, integer, email} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "changeLead",
    data(){
        return {
            errors:{}
        }
    },
    props:["idTarget","idLead"],
    setup(props){
        const emitter = inject('emitter');
        const {id} = toRefs(props)

        const {idTarget,idLead} = toRefs(props);
        const {t} = useI18n({});
        let loading = ref(false);
        let areas = ref([]);
        let provinces = ref([]);


        let getStore= () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/create`)
            .then((res) => {
                let l = res.data.data;
                provinces.value = l.provinces;
            })
            .catch((err) => {
                this.errors = err.response.data.errors;
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        };

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

        let getLead = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/salesLead/${idLead.value}/edit`)
            .then((res) => {
                let l = res.data.data;
                addLead.data.name = l.lead.name;
                addLead.data.address = l.lead.address;
                addLead.data.phone = l.lead.phone;
                addLead.data.email = l.lead.email;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getLead();
            getStore();
        });


        //start design
        let addLead =  reactive({
            data:{
                name: '',
                address: '',
                phone: '',
                email: '',
                province_id : null,
                area_id : null,
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(40),
                    required
                },
                address: {
                    minLength: minLength(3),
                    maxLength:maxLength(150),
                },
                phone: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                email: {
                    email
                },
                province_id: {
                    required,
                    integer
                },
                area_id: {
                    required,
                    integer
                },

            }
        });


        const v$ = useVuelidate(rules,addLead.data);


        return {t,idTarget,loading,...toRefs(addLead),provinces,areas,getAreas,v$};
    },
    methods: {
        editLead(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/changeLeadClient/${this.idLead}`,this.data)
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
                    this.errors = err.response;
                })
                .finally(() => {
                    this.loading = false;
                });

            }
        },
        resetForm(){
            this.name = '';
            this.address = '';
            this.phone = '';
            this.email = '';
            this.seller_category_id = idTarget;
            this.province_id = null;
            this.area_id = null;
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
