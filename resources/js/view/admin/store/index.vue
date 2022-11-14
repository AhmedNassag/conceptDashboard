<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('store.store') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('store.store') }}</li>
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
                                            v-if="permission.includes('store create')"
                                           :to="{name: 'createStore'}"
                                            class="btn btn-custom btn-warning">
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Name') }}</th>
                                        <th>الموبيل</th>
                                        <th>المحافظه</th>
                                        <th>المنطقه</th>
                                        <th>{{ $t('global.Status') }}</th>
                                        <th>مخزن التطبيق</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="stores.length">
                                    <tr v-for="(item,index) in stores"  :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.phone }}</td>
                                        <td>{{ item.province.name }}</td>
                                        <td>{{ item.area.name }}</td>
                                        <td>
                                            <a href="#" @click="activationStore(item.id,item.status,index)">
                                                <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                                            </a>
                                        </td>

                                        <td>
                                            <a href="#" @click="mainStore(item.id,item.main,index)">
                                                <span :class="[parseInt(item.main) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.main) ? 'رئيسي' : 'ليس رئيسي' }}</span>
                                            </a>
                                        </td>
                                        <td>

                                            <router-link
                                                :to="{name: 'editStore',params:{id:item.id}}"
                                               v-if="permission.includes('store edit')"
                                               class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <router-link
                                                :to="{name: 'showStore',params:{id:item.id}}"
                                               v-if="permission.includes('store read')"
                                               class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-book-open"></i> {{$t('global.showProducts')}}
                                            </router-link>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="7">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2" :data="storesPaginate" @pagination-change-page="getDiscount">
                <template #prev-nav>
                    <span>&lt; {{$t('global.Previous')}}</span>
                </template>
                <template #next-nav>
                    <span>{{$t('global.Next')}} &gt;</span>
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
        let stores = ref([]);
        let storesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getDiscount = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    storesPaginate.value = l.stores;
                    stores.value = l.stores.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getDiscount();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getDiscount();
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


        function activationStore(id, active,index) {
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

                    adminApi.get(`/v1/dashboard/activationStore/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? 'تم ايقاف التفعيل بنجاح' :'تم التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            stores.value[index]['status'] =  active ? 0:1
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

        function mainStore(id, main,index) {
            Swal.fire({
                title: `${main ? 'هل انت متاكد من ايقاف المخزن من التطبيق ؟' : 'هل انت متاكد من جعل المخزن رئيسي فى التطبيق ؟'} `,
                text: `برجاء العلم ان فى حالة اختيار المخزن سوف يتم عرض منتجاتة فى التطبيق عند العملاء المسجلين على منطقة لاتدعمها المخازن ولن يتم اجراء اى طلب على هذا النوع من العملاء`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/mainStore/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${main ? 'تم ايقاف المخزن بنجاح' : 'تم التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            stores.value.forEach((el) => {
                                el.main = 0;
                            });
                            stores.value[index]['main'] =  main ? 0:1
                        })
                        .catch((err) => {
                            console.log(err.response);
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يجب اختيار مخزن اخر يكون رئيسي وسوف يتم ايقاف هذا المخزن تلقائيا!`,
                            });
                        });
                }
            });
        }

        return {dateFormat,stores, loading,permission, getDiscount, search, activationStore,mainStore, storesPaginate};

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
