<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications :position="'top left'"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('sidebar.setting')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexSetting'}">{{$t('sidebar.setting')}}</router-link></li>
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
                                    :to="{name: 'indexSetting'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    {{$t('global.back')}}
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['close']">{{ errors['close'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['show_price']">{{ errors['show_price'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['wats_app']">{{ errors['wats_app'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['facebook']">{{ errors['facebook'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['phone']">{{ errors['phone'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['hotLine']">{{ errors['hotLine'][0] }}<br /> </div>
                                    <form @submit.prevent="editJob" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.Phone') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.trim="v$.phone.$model"
                                                       :class="{'is-invalid':v$.phone.$error,'is-valid':!v$.phone.$invalid}"
                                                       :placeholder="$t('global.Phone')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.phone.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span v-if="v$.phone.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{v$.phone.minLength.$params.min}} {{ $t('global.Letters') }} <br/></span>
                                                    <span v-if="v$.phone.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{v$.phone.maxLength.$params.max}} {{ $t('global.Letters') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.hotLine') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.trim="v$.hotLine.$model"
                                                       :class="{'is-invalid':v$.hotLine.$error,'is-valid':!v$.hotLine.$invalid}"
                                                       :placeholder="$t('global.Phone')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.hotLine.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.watsApp') }}</label>
                                                <input type="number"
                                                       class="form-control"
                                                       v-model.trim="v$.wats_app.$model"
                                                       :class="{'is-invalid':v$.wats_app.$error,'is-valid':!v$.wats_app.$invalid}"
                                                       :placeholder="$t('global.watsApp')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span
                                                        v-if="v$.wats_app.required.$invalid">{{ $t('global.PhoneIsRequired') }} <br/></span>
                                                    <span
                                                        v-if="v$.wats_app.minLength.$invalid">{{ $t('global.PhoneIsMustHaveAtLeast') }} {{
                                                            v$.wats_app.minLength.$params.min
                                                        }} {{ $t('global.Letters') }} <br/></span>
                                                    <span
                                                        v-if="v$.wats_app.maxLength.$invalid">{{ $t('global.PhoneIsMustHaveAtMost') }} {{
                                                            v$.wats_app.maxLength.$params.max
                                                        }} {{ $t('global.Letters') }} </span>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.facebook') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.facebook.$model"
                                                       :class="{'is-invalid':v$.facebook.$error,'is-valid':!v$.facebook.$invalid}"
                                                       :placeholder="$t('global.facebook')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.facebook.required.$invalid">{{ $t('global.IsRequired') }} <br/></span>
                                                    <span v-if="v$.facebook.minLength.$invalid">
                                                        {{ $t('global.IsMustHaveAtLeast') }} {{ v$.facebook.minLength.$params.min }} {{ $t('global.Letters') }} <br/>
                                                    </span>
                                                </div>
                                            </div>

                                            <!--start twitter-->
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.twitter') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.twitter.$model"
                                                       :class="{'is-invalid':v$.twitter.$error,'is-valid':!v$.twitter.$invalid}"
                                                       :placeholder="$t('global.twitter')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.twitter.required.$invalid">{{ $t('global.IsRequired') }} <br/></span>
                                                    <span v-if="v$.twitter.minLength.$invalid">
                                                        {{ $t('global.IsMustHaveAtLeast') }} {{ v$.twitter.minLength.$params.min }} {{ $t('global.Letters') }} <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--end twitter-->

                                            <!--start instgram-->
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.instgram') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.instgram.$model"
                                                       :class="{'is-invalid':v$.instgram.$error,'is-valid':!v$.instgram.$invalid}"
                                                       :placeholder="$t('global.instgram')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.instgram.required.$invalid">{{ $t('global.IsRequired') }} <br/></span>
                                                    <span v-if="v$.instgram.minLength.$invalid">
                                                        {{ $t('global.IsMustHaveAtLeast') }} {{ v$.instgram.minLength.$params.min }} {{ $t('global.Letters') }} <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--end instgram-->

                                            <!--start linkedIn-->
                                            <div class="col-md-4 mb-3">
                                                <label>{{ $t('global.linkedIn') }}</label>
                                                <input type="text"
                                                       class="form-control"
                                                       v-model.trim="v$.linkedIn.$model"
                                                       :class="{'is-invalid':v$.linkedIn.$error,'is-valid':!v$.linkedIn.$invalid}"
                                                       :placeholder="$t('global.linkedIn')"
                                                >
                                                <div class="valid-feedback">{{ $t('global.LooksGood') }}</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.linkedIn.required.$invalid">{{ $t('global.IsRequired') }} <br/></span>
                                                    <span v-if="v$.linkedIn.minLength.$invalid">
                                                        {{ $t('global.IsMustHaveAtLeast') }} {{ v$.linkedIn.minLength.$params.min }} {{ $t('global.Letters') }} <br/>
                                                    </span>
                                                </div>
                                            </div>
                                            <!--end linkedIn-->

                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom02">{{$t('global.closeApp')}}</label>
                                                <input type="checkbox" id="validationCustom02" v-model="data.close" class="m-5" :checked="data.close">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label>{{$t('global.showPrice')}}</label>
                                                <input type="checkbox"  v-model="data.show_price" class="m-5" :checked="data.show_price">
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
import {required, minLength, maxLength, numeric} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";


export default {
    name: "editJob",
    data(){
        return {
            errors:{}
        }
    },
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        const {t} = useI18n({});
        let job = ref({});
        let loading = ref(false);

        let getMainJobViews = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/setting/${id.value}/edit`)
                .then((res) => {
                    let l = res.data.data;
                    addJob.data.close = l.setting.close;
                    addJob.data.show_price = l.setting.show_price;
                    addJob.data.phone = l.setting.phone;
                    addJob.data.hotLine = l.setting.hotLine;
                    addJob.data.wats_app = l.setting.wats_app;
                    addJob.data.facebook = l.setting.facebook;
                    addJob.data.twitter = l.setting.twitter;
                    addJob.data.instgram = l.setting.instgram;
                    addJob.data.linkedIn = l.setting.linkedIn;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                })
        }

        onMounted(() => {
            getMainJobViews();
        });

        let addJob =  reactive({
            data:{
                close:0,
                show_price:0,
                phone: '',
                hotLine: '',
                wats_app: '',
                facebook: '',
                twitter: '',
                instgram: '',
                linkedIn: '',
            }
        });

        const rules = computed(() => {
            return {
                phone: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                hotLine: {
                    required,
                },
                wats_app: {
                    required,
                    minLength: minLength(8),
                    maxLength: maxLength(20)
                },
                facebook: {
                    required,
                    minLength: minLength(1),
                },
                twitter: {
                    required,
                    minLength: minLength(1),
                },
                instgram: {
                    required,
                    minLength: minLength(1),
                },
                linkedIn: {
                    required,
                    minLength: minLength(1),
                },
            }
        });


        const v$ = useVuelidate(rules,addJob.data);


        return {t,loading,...toRefs(addJob),v$};
    },
    methods: {
        editJob(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/setting/${this.id}`,this.data)
                    .then((res) => {
                        notify({
                            title: `${this.t('global.EditSuccessfully')} <i class="fas fa-check-circle"></i>`,
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
</style>
