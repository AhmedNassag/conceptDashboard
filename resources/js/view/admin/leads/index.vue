<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('sidebar.lead') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('sidebar.lead') }}</li>
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
                                            :to="{name: 'createLead'}"
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
                                        <th>العنوان</th>
                                        <th>نوعه</th>
                                        <th>الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="clients.length">
                                    <tr v-for="(item,index) in clients"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.phone }}</td>
                                        <td>{{ item.address }}</td>
                                        <td>{{ item.type }}</td>
                                        <td>
                                            <router-link
                                                :to="{name: 'editLead',params:{id:item.id}}"
                                                v-if="permission.includes('category edit')"
                                                :title="$t('global.Edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <router-link :to="{name: 'showLead',params:{id:item.id}}" class="btn btn-sm btn-info me-2" >
                                                <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                            </router-link>

                                            <a href="javascript:void(0);"
                                               v-if="item.lead_client"
                                               class="btn btn-sm btn-info me-2"
                                               data-bs-toggle="modal"
                                               :data-bs-target="'#edit-category'+item.id"
                                            >
                                                <i class="fas fa-book-open"></i> {{$t('global.Show')}}
                                            </a>

                                            <a href="#" @click="deleteMeasure(item.id,index)"
                                               v-if="permission.includes('measureUnit delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                        <!-- invoice big Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id" v-if="item.lead_client">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"
                                                        >
                                                            التفاصيل
                                                        </h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
                                                                <div class="tab-content pt-0">
                                                                    <div role="tabpanel" :id="'tab-4'" class="tab-pane fade active show">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-center table-hover mb-0 datatable">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>الاسم</th>
                                                                                    <th>{{ item.name }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>الموبايل</th>
                                                                                    <th>{{ item.phone }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>العنوان</th>
                                                                                    <th>{{ item.address }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>النوع</th>
                                                                                    <th>{{ item.type }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>المصدر</th>
                                                                                    <th>{{ item.lead_client.lead_source.name }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>المتابعه</th>
                                                                                    <th>{{ item.lead_client.lead_follow.name }}</th>
                                                                                </tr>
                                                                                </thead>
                                                                            </table>
                                                                        </div>

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

            adminApi.get(`/v1/dashboard/lead?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    clientsPaginate.value = l.leads;
                    clients.value = l.leads.data;
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

        function deleteMeasure(id, index) {
            Swal.fire({
                title: `هل تريد هذف هذا العنصر ؟ `,
                text: `لن تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/lead/${id}`)
                        .then((res) => {
                            clients.value.splice(index,  1);

                            Swal.fire({
                                icon: 'success',
                                title: `تم الحذف بنجاح`,
                                showConfirmButton: false,
                                timer: 1500
                            });
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


        return {getClient,deleteMeasure, loading,permission, search, clientsPaginate,clients,t};

    },methods: {
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
