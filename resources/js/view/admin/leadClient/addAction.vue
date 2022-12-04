<template>
    <div :class="['page-wrapper','page-wrapper-ar']">

        <div class="content container-fluid">

            <notifications position="top left"  />

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('sidebar.leadClient') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{name: 'indexLeadClient'}">{{ $t('sidebar.leadClient') }}</router-link></li>
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
                                    :to="{name: 'indexLeadClient'}"
                                    class="btn btn-custom btn-dark"
                                >
                                    العوده للخلف
                                </router-link>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-danger text-center" v-if="errors['action']">{{ errors['action'][0] }}<br /> </div>
                                    <div class="alert alert-danger text-center" v-if="errors['lead_id']">{{ errors['lead_id'][0] }}<br /> </div>
                                    <form @submit.prevent="storeClient" class="needs-validation">
                                        <div class="form-row row">

                                            <!--Start action-->
                                            <div class="col-md-6 mb-3">
                                                <label>نوع التفاعل</label>
                                                <textarea type="text" class="form-control"
                                                   v-model.trim="v$.action.$model"
                                                   placeholder="اكتب ما تم مع العميل هنا "
                                                   :class="{'is-invalid':v$.action.$error,'is-valid':!v$.action.$invalid}"
                                                ></textarea>
                                                <div class="valid-feedback">تبدو جيده</div>
                                                <div class="invalid-feedback">
                                                    <span v-if="v$.action.required.$invalid"> هذا الحقل مطلوب<br /> </span>
                                                    <span v-if="v$.action.minLength.$invalid"> يجب ان يكون علي الاقل {{ v$.action.minLength.$params.min }} حرف  <br /></span>
                                                </div>
                                            </div>
                                            <!--End action-->

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
    props:["id"],
    setup(props){
        const {id} = toRefs(props)
        let loading = ref(false);

        //start design
        let addClient =  reactive({
            data:{
                action : '',
                lead_id : parseInt(id.value),
            }
        });

        const rules = computed(() => {
            return {
                action: {
                    minLength: minLength(3),
                    required
                },
                lead_id: {
                    required
                },
            }
        });

        const v$ = useVuelidate(rules,addClient.data);

        return {loading,...toRefs(addClient),v$};
    },
    methods: {
        storeClient(){
            this.v$.$validate();

            if(!this.v$.$error){

                this.loading = true;
                this.errors = {};

                adminApi.post(`/v1/dashboard/addAction`,this.data)
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
                    this.errors = err.response.data.errors;
                })
                .finally(() => {
                    this.loading = false;
                });

            }
        },
        resetForm(){
            this.data.action = '';
            this.data.lead_id = parseInt(id.value);
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
