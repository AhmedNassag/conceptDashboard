<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">عملاء الصيانة</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexPeriodicMaintenance'}">عملاء الصيانة</router-link></li>
                            <li class="breadcrumb-item active">تعديل عميل صيانة</li>
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
                                    :to="{name: 'indexPeriodicMaintenance'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <form @submit.prevent="editPeriodicMaintenance" class="needs-validation">
                                        <div class="form-row row">

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">اسم العميل</label>
                                                <input type="text" class="form-control"
                                                    v-model.trim="v$.name.$model"
                                                    id="validationCustom01"
                                                    placeholder="اسم العميل"
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
                                                <label for="validationCustom01">عدد الأجهزة</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.quantity.$model"
                                                    id="validationCustom02"
                                                    placeholder="عدد الأجهزة"
                                                    :class="{'is-invalid':v$.quantity.$error,'is-valid':!v$.quantity.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.quantity.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">سعر الصيانة</label>
                                                <input type="number" class="form-control"
                                                    v-model.trim="v$.price.$model"
                                                    id="validationCustom03"
                                                    placeholder="سعر الصيانة"
                                                    :class="{'is-invalid':v$.price.$error,'is-valid':!v$.price.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.price.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">موعد الصيانة القادم</label>
                                                <input type="date" class="form-control"
                                                    v-model.trim="v$.next_maintenance.$model"
                                                    id="validationCustom04"
                                                    placeholder="موعد الصيانة القادم"
                                                    :class="{'is-invalid':v$.next_maintenance.$error,'is-valid':!v$.next_maintenance.$invalid}"
                                                >
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.next_maintenance.required.$invalid"> هذا الحقل مطلوب<br /> </span>
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
import {computed, onMounted, reactive,toRefs,inject,ref} from "vue";
import useVuelidate from '@vuelidate/core';
import {required, minLength, maxLength, integer} from '@vuelidate/validators';
import adminApi from "../../../api/adminAxios";
import { notify } from "@kyvg/vue3-notification";


export default {
    name: "editPeriodicMaintenance",
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


        let getPeriodicMaintenance = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/periodicMaintenance/${id.value}/edit`)
            .then((res) => {
                let l = res.data.data;
                addPeriodicMaintenance.data.order_id  = l.periodicMaintenance.order_id ;
                addPeriodicMaintenance.data.name = l.periodicMaintenance.name;
                addPeriodicMaintenance.data.quantity = l.periodicMaintenance.quantity;
                addPeriodicMaintenance.data.price = l.periodicMaintenance.price;
                addPeriodicMaintenance.data.collector = l.periodicMaintenance.collector;
                addPeriodicMaintenance.data.next_maintenance = l.periodicMaintenance.next_maintenance;
                addPeriodicMaintenance.data.note = l.periodicMaintenance.note;
            })
            .catch((err) => {
                console.log(err.response);
            })
            .finally(() => {
                loading.value = false;
            })
        }

        onMounted(() => {
            getPeriodicMaintenance();
        });


        //start Supplier
        let addPeriodicMaintenance =  reactive({
            data:{
                name : '',
                quantity : '',
                price : '',
                collector : '',
                next_maintenance : '',
                note : '',
            }
        });

        const rules = computed(() => {
            return {
                name: {
                    minLength: minLength(3),
                    maxLength:maxLength(70),
                    required
                },
                quantity: {
                    required
                },
                price: {
                    required
                },
                next_maintenance: {
                    required
                },
            }
        });


        const v$ = useVuelidate(rules,addPeriodicMaintenance.data);


        return {loading,...toRefs(addPeriodicMaintenance),v$};
    },
    methods: {
        editPeriodicMaintenance(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.put(`/v1/dashboard/periodicMaintenance/${this.id}`,this.data)
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
</style>
