<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">جدول مواعيد الصيانات</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">جدول مواعيد الصيانات</li>
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
                                            v-if="permission.includes('periodicMaintenance create')"
                                            :to="{name: 'createPeriodicMaintenance'}"
                                            class="btn btn-custom btn-warning">
                                            إضافة
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">رقم الطلب</th>
                                        <th class="text-center">اسم العميل</th>
                                        <th class="text-center">عدد الأجهزة</th>
                                        <th class="text-center">سعر الصيانة</th>
                                        <th class="text-center">مبلغ التحصيل</th>
                                        <th class="text-center">موعد الصيانة القادم</th>
                                        <th class="text-center">ملاحظات</th>
                                        <th class="text-center">تاريخ التركيب</th>
                                        <th class="text-center">حالة التفعيل</th>
                                        <th class="text-center">الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="periodicMaintenances.length">
                                    <tr v-for="(item,index) in periodicMaintenances" :key="item.id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">{{ item.order_id ? item.order_id : '---' }}</td>
                                        <td class="text-center">{{ item.name ? item.name : '---' }}</td>
                                        <td class="text-center">{{ item.quantity ? item.quantity : '---' }}</td>
                                        <td class="text-center">{{ item.price ? item.price : '---' }}</td>
                                        <td class="text-center">{{ item.collector ? item.collector : '---' }}</td>
                                        <td class="text-center">{{ item.next_maintenance ? item.next_maintenance : '---' }}</td>
                                        <td class="text-center">{{ item.note ? item.note : '---' }}</td>
                                        <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                        <td class="text-center">
                                            <a href="#" @click="activationPeriodicMaintenance(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">
                                                    {{ parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">

                                            <router-link
                                               :to="{name: 'editPeriodicMaintenance',params:{id:item.id}}"
                                               v-if="permission.includes('periodicMaintenance edit')"
                                               class="btn btn-sm btn-success me-2">
                                               <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deletePeriodicMaintenance(item.id,index)"
                                               v-if="permission.includes('periodicMaintenance delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                               <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="5">لا يوجد بيانات</th>
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
            <Pagination :limit="2" :data="periodicMaintenancesPaginate" @pagination-change-page="getPeriodicMaintenance">
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
import {onMounted, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    setup() {

        // get packages
        let periodicMaintenances = ref([]);
        let periodicMaintenancesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getPeriodicMaintenance = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/periodicMaintenance?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    periodicMaintenancesPaginate.value = l.periodicMaintenances;
                    periodicMaintenances.value = l.periodicMaintenances.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getPeriodicMaintenance();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getPeriodicMaintenance();
            }
        });

        let dateFormat = (item) => {
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                 ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        };


        function deletePeriodicMaintenance(id, index) {
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

                    adminApi.delete(`/v1/dashboard/periodicMaintenance/${id}`)
                    .then((res) => {
                        periodicMaintenances.value.splice(index,  1);

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

        function activationPeriodicMaintenance(id, active,index) {
            Swal.fire({
                title: `${active ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل  ؟'} `,
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationPeriodicMaintenance/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: `${active ? 'تم التفعيل بنجاح' :'تم ايقاف التفعيل بنجاح'}`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        periodicMaintenances.value[index]['status'] =  active ? 0:1
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

        return {dateFormat,periodicMaintenances, loading,permission, getPeriodicMaintenance, search, deletePeriodicMaintenance, activationPeriodicMaintenance, periodicMaintenancesPaginate};

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin")
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

</style>
