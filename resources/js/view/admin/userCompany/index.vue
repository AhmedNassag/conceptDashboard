<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title"> {{$t('sidebar.userCompany')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('sidebar.userCompany')}}</li>
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
                            <div class="card-header pt-0">
                                <div class="row justify-content-between">
                                    <div class="col-5">
                                        بحث :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createUserCompany'}"
                                            class="btn btn-custom btn-warning">
                                            اضافه
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>رقم الهاتف</th>
                                        <th>{{$t('global.sellingMethod')}}</th>
                                        <th>العنوان</th>
                                        <th>الحاله</th>
                                        <th>الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="clients.length">
                                    <tr v-for="(item,index) in clients"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.phone }}</td>
                                        <td>{{ item.complement.selling_method.name }}</td>
                                        <td>{{ item.user_company.address }}</td>
                                        <td>
                                            <a href="#" @click="activationClient(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                                            </a>
                                        </td>
                                        <td>
                                            <router-link
                                                :to="{name: 'editUserCompany',params:{id:item.id}}"
                                                v-if="permission.includes('category edit')"
                                                :title="$t('global.Edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <router-link :to="{name: 'showUserCompany',params:{id:item.id}}"
                                               class="btn btn-sm btn-info me-2" >
                                                <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                            </router-link>
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal" @click="userNotification(item.id)"
                                               :data-bs-target="'#edit-category'+item.id" :title="$t('global.sendNotification')">
                                                <i class="fas fa-comment-dots"></i>
                                            </a>
                                        </td>

                                        <!-- invoice big Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.sendNotification') }}</h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
                                                                <div class="tab-content pt-0">
                                                                    <loader v-if="loading"/>
                                                                    <div class="alert alert-danger text-center" v-if="errors['notification']">{{ errors['notification'][0] }}<br /> </div>
                                                                    <div class="table-responsive">
                                                                        <form @submit.prevent="storeJob" class="needs-validation">
                                                                            <div class="form-row row">
                                                                                <div class="col-md-12 mb-3">
                                                                                    <label>{{$t('global.theNotification')}}</label>
                                                                                    <textarea rows="4" cols="5" v-model.trim="v$.notification.$model" :class="['form-control text-height',{'is-invalid':v$.notification.$error,'is-valid':!v$.notification.$invalid}]" :placeholder="$t('global.theNotification')"></textarea>
                                                                                    <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                    <div class="invalid-feedback">
                                                                                        <span v-if="v$.notification.required.$invalid">{{$t('global.ThisFieldIsRequired')}}<br /> </span>
                                                                                        <span v-if="v$.notification.minLength.$invalid">{{$t('global.IsMustHaveAtLeast')}} {{ v$.notification.minLength.$params.min }} {{$t('global.Letters')}} <br /></span>
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
                                            </div>
                                        </div>
                                        <!-- /invoice big Modal-->

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="7">لا يوجد بيانات</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :limit="2" :data="clientsPaginate" @pagination-change-page="getClient">
                <template #prev-nav>
                    <span>&lt; السابق</span>
                </template>
                <template #next-nav>
                    <span>التالي &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, watch, ref, computed, reactive, toRefs} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {minLength, numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import {notify} from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {

        // get packages
        let clients = ref([]);
        let clientsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        const {t} = useI18n({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getClient = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/userCompany?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    clientsPaginate.value = l.users;
                    clients.value = l.users.data;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getClient();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getClient();
            }
        });

        function activationClient(id, status,index) {
            Swal.fire({
                title: `${parseInt(status) ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل  ؟'} `,
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationCompany/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${parseInt(status) ? 'تم ايقاف التفعيل بنجاح' : 'تم التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            clients.value[index]['status'] =  parseInt(status) ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
                            });
                        });
                }
            });
        }

        let addJob =  reactive({
            data:{
                notification:'',
                user_id:''
            },
        });

        const rules = computed(() => {
            return {
                notification:{
                    required,
                    minLength: minLength(5),
                },
            }
        });

        let close=(id)=>{
            document.getElementById('close-'+id).click();
        };

        let userNotification=(id)=>{
            addJob.data.user_id = id;
        };

        const v$ = useVuelidate(rules,addJob.data);

        return {getClient,close,userNotification, loading,permission, search,...toRefs(addJob),v$, activationClient, clientsPaginate,clients,t};

    },methods: {
        storeJob(){
            this.v$.$validate();
            if(!this.v$.$error){
                this.loading = true;
                this.errors = {};
                adminApi.post(`/v1/dashboard/sendClientNotification`,this.data)
                    .then((res) => {

                        notify({
                            title: `${this.t('global.cashReceivedSuccessfully')} <i class="fas fa-check-circle"></i>`,
                            type: "success",
                            duration: 5000,
                            speed: 2000
                        });

                        document.getElementById('close-'+this.data.user_id).click();
                        this.resetForm();
                        this.$nextTick(() => { this.v$.$reset() });
                    })
                    .catch((err) => {
                        // console.log(err);
                        this.errors = err.response.data.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        },
        resetForm(){
            this.data.notification = '';
            this.data.user_id = '';
        }
    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
            errors:{}
        }
    }
}
</script>

<style scoped>
.card {
    position: relative;
}

.btn-custom {
    width: 30%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
    width: 35%;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

.amount h5{
    font-weight: 700;
    text-align: center;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}

.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}

.edit-envoice .modal-dialog{
    max-width: 500px !important;
}

/*======== print ===========*/

.image-div img {
    width: 35%;
}

img {
    max-width: inherit;
    width: inherit;
}
</style>
