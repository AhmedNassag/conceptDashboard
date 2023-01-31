<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">إدارة خدمة العملاء</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">خدمة العملاء</li>
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
                                    <div class="col-12 row">
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createClient'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            تسجيل عميل
                                        </router-link>
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createOrderDirect'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            إصدار فاتورة
                                        </router-link>
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createProblemLeadsManagement', params: {lang: locale || 'ar'}}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            تسجيل عطل
                                        </router-link>
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createPeriodicMaintenance'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            تسجيل صيانة
                                        </router-link>
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createCategory'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            خط سير المناديب
                                        </router-link>

                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'indexComplaintClient'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            عرض الشكاوى
                                        </router-link>

                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'indexLeadsManagement'}"
                                            class="btn btn-custom btn-warning mr-1 col-1">
                                            عملاء محتملين
                                        </router-link>

                                        <hr style="color:#FFF;">

                                        <div class="col-5">
                                            بحث بالإسم / الكود/ رقم الموبايل :
                                            <input type="search" v-model="search" class="custom"/>
                                        </div>
                                        <div class="col-2">
                                            <!-- بحث :
                                            <input type="search" v-model="search" class="custom"/> -->
                                        </div>

                                        <div class="col-5">
                                            <!-- <form @submit.prevent="getCallCenter" class="needs-validation">
                                                {{$t('global.PurchaseInvoiceNumber')}}
                                                <input type="number" class="custom">
                                                <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                            </form> -->
                                            بحث بالمخزن / المحافظة/ المنطقة :
                                            <input type="search" v-model="search2" class="custom"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr style="background-color:gainsboro">
                                            <th class="text-center">#</th>
                                            <th class="text-center">اسم العميل</th>
                                            <th class="text-center">رقم التليفون</th>
                                            <th class="text-center">كود العميل</th>
                                            <th class="text-center">المخزن</th>
                                            <th class="text-center">المحافظة</th>
                                            <th class="text-center">المنطقة</th>
                                            <th class="text-center">نوع الجهاز</th>
                                            <th class="text-center">سعر الجهاز</th>
                                            <th class="text-center">موعد الطلب</th>
                                            <th class="text-center">حالة الطلب</th>
                                            <th class="text-center">تاريخ التركيب</th>
                                            <th class="text-center">اسم الفنى</th>
                                            <th class="text-center">ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="callCenters.length">
                                        <tr v-for="(item,index) in callCenters" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">
                                                <router-link :to="{name: 'profileClient',params:{id:item.user.id}}" class="btn btn-sm btn-info me-2" >
                                                    {{ item.user && item.user.name ? item.user.name : '---' }}
                                                </router-link>
                                            </td>
                                            <td class="text-center">{{ item.user && item.user.phone ? item.user.phone : '---' }}</td>
                                            <td class="text-center">{{ item.user && item.user.user_code ? item.user.user_code : '---' }}</td>
                                            <td class="text-center">{{ item.store && item.store.name ? item.store.name : '---' }}</td>
                                            <td class="text-center">{{ item.store && item.store.area.province.name ? item.store.area.province.name : '---' }}</td>
                                            <td class="text-center">{{ item.store && item.store.area.name ? item.store.area.name : '---' }}</td>
                                            <td class="text-center">{{ item.orderDetails && item.orderDetails[0].product_id ? item.orderDetails[0].product_id : '---' }}</td>
                                            <td class="text-center">{{ item.total ? item.total : '---' }}</td>
                                            <td class="text-center">{{ dateFormat(item.created_at) }}</td>
                                            <td class="text-center">{{ item.order_status ? item.order_status.name : '---' }}</td>
                                            <td class="text-center">{{ item.periodic_maintenance && item.periodic_maintenance.created_at ? dateFormat(item.periodic_maintenance.created_at) : '---' }}</td>
                                            <td class="text-center">{{ item.representative ? item.representative.name : '---' }}</td>
                                            <td class="text-center">{{ item.id }}</td>
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
            <Pagination :limit="2" :data="callCentersPaginate" @pagination-change-page="getCallCenter">
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
import {onMounted, watch, ref, toRefs, computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    props:["id"],
    setup(props) {

        // get packages
        let callCenters = ref([]);
        let callCentersPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        const search2 = ref('');
        let store = useStore();
        const {id} = toRefs(props)

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getCallCenter = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/callCenter?page=${page}&search=${search.value}&search2=${search2.value}`)
            .then((res) => {
                let l = res.data.data;
                callCentersPaginate.value = l.callCenters;
                callCenters.value = l.callCenters.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getCallCenter();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getCallCenter();
            }
        });
        watch(search2, (search2, prevSearch2) => {
            if (search2.length >= 0) {
                getCallCenter();
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

        return {id, getCallCenter, loading,permission, search, search2, dateFormat, callCentersPaginate,callCenters};

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

.custom-img {
    width: 100px;
}
</style>
