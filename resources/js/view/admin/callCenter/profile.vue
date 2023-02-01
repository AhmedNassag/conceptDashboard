<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.showInformationClient') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexCallCenter'}">
                                    {{ $t('global.clients') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Show') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-body">

                            <router-link
                                :to="{name: 'indexCallCenter'}"
                                class="btn btn-custom btn-dark me-2 mr-3"
                            >
                                العوده للخلف
                            </router-link>

                            <!-- <router-link
                                :to="{name: 'editClient',params:{id}}"
                                v-if="permission.includes('category edit')"
                                class="btn btn-sm btn-success me-2 mr-3">
                                <i class="far fa-edit"></i> {{$t('global.updateUserData')}}
                            </router-link>

                            <a href="#" @click="activationClient(user.id,user.status)">
                                <span :class="[parseInt(user.status) ? 'text-success hover': 'text-danger hover']">{{parseInt(user.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                            </a> -->

                            <div class="text-center mb-5">
                                <label class="avatar avatar-xxl profile-cover-avatar" >
                                    <img
                                        class="avatar-img"
                                        :src="user.image_path"
                                        onerror="src='/admin/img/admin.jpeg'"
                                        alt="Profile Image"
                                    >
                                </label>
                                <h2>
                                    {{user.name}}
                                    <i class="fas fa-certificate text-primary small" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i>
                                </h2>

                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <i class="fas fa-phone"></i> <span>{{user.phone}}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fas fa-map-marker-alt"></i> <span>{{client.address}}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="far fa-calendar-alt"></i> <span>{{dateFormat(user.created_at)}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="row">

                                <!--تاريخ التركيبات-->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="text-center card-title">تاريخ التركيبات</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr style="background-color:gainsboro">
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">نوع الجهاز</th>
                                                                <th class="text-center">السعر</th>
                                                                <th class="text-center">الكمية</th>
                                                                <th class="text-center">تاريخ الطلب</th>
                                                                <th class="text-center">حالة الطلب</th>
                                                                <th class="text-center">نوع الطلب</th>
                                                                <th class="text-center">نوع البيع</th>
                                                                <th class="text-center">اسم الفنى</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody v-if="orders.length">
                                                            <tr v-for="(item,index) in orders" :key="item.id">
                                                                <td class="text-center">{{ index + 1 }}</td>
                                                                <td class="text-center">{{ item.order_details.id ? item.order_details.id : '---' }}</td>
                                                                <td class="text-center">{{ item.total ? item.total : '---' }}</td>
                                                                <td class="text-center">{{ item.order_details && item.order_details.quantity ? item.order_details.quantity : '---' }}</td>
                                                                <td class="text-center">{{ item.created_at ? dateFormat(item.created_at) : '---' }}</td>
                                                                <td class="text-center">{{ item.order_status ? item.order_status.name : '---' }}</td>
                                                                <td class="text-center">{{ item.is_online == 1 ? 'أون لاين' : 'مباشر' }}</td>
                                                                <td class="text-center">{{ item.order_details && item.order_details.id ? item.order_details.id : '---' }}</td>
                                                                <td class="text-center">{{ item.representative ? item.representative.user.name : '---' }}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody v-else>
                                                            <tr>
                                                                <th class="text-center" colspan="10">لا يوجد بيانات</th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--تاريخ الصيانات-->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="text-center card-title">تاريخ الصيانات</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr style="background-color:gainsboro">
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">رقم الطلب</th>
                                                                <th class="text-center">عدد الأجهزة</th>
                                                                <th class="text-center">اسم الشمعة</th>
                                                                <th class="text-center">تاريخ الصيانة</th>
                                                                <th class="text-center">حالة الصيانة</th>
                                                                <th class="text-center">اسم الفنى</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody v-if="maintenances.length">
                                                            <tr v-for="(item,index) in maintenances" :key="item.id">
                                                                <td class="text-center">{{ index + 1 }}</td>
                                                                <td class="text-center">{{ item.order_id }}</td>
                                                                <td class="text-center">{{ item.quantity }}</td>
                                                                <td class="text-center">{{ item.price }}</td>
                                                                <td class="text-center">{{ item.next_maintenance }}</td>
                                                                <td class="text-center">{{ item.status == 1 ? 'تمت بالفعل' : 'لم تتم بعد' }}</td>
                                                                <td class="text-center">{{ item.order && item.order.representative ? item.order.representative.user.name : '---' }}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody v-else>
                                                            <tr>
                                                                <th class="text-center" colspan="10">لا يوجد بيانات</th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--تاريخ الأعطال-->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="text-center card-title">تاريخ الأعطال</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr style="background-color:gainsboro">
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">وصف العطل</th>
                                                                <th class="text-center">تاريخ العطل</th>
                                                                <th class="text-center">موظف الأعطال</th>
                                                                <th class="text-center">كيفية التعامل</th>
                                                                <th class="text-center">تاريخ التعامل</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody v-if="problems.length">
                                                            <tr v-for="(item,index) in problems" :key="item.id">
                                                                <td class="text-center">{{ index + 1 }}</td>
                                                                <td class="text-center">{{ item.description }}</td>
                                                                <td class="text-center">{{ item.created_at ? dateFormat(item.created_at) : '---' }}</td>
                                                                <td class="text-center">{{ item.employee ? item.employee.user.name : '---' }}</td>
                                                                <td class="text-center">{{ item.comments ? item.comments.id : '---' }}</td>
                                                                <td class="text-center">{{ item.comments.id }}</td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody v-else>
                                                            <tr>
                                                                <th class="text-center" colspan="10">لا يوجد بيانات</th>
                                                            </tr>
                                                        </tbody>
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
            </div>

        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, watch, ref, computed, toRefs} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    props: ["id"],
    setup(props) {

        const {id} = toRefs(props);
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let date_now = new Date().toISOString().split('T')[0];
        let user = ref({});
        let client = ref({});

        let orders = ref({});
        let maintenances = ref({});
        let problems = ref({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getCallCenters = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/callCenter/${id.value}`)
                .then((res) => {
                    let l = res.data.data;

                    user.value = l.user;
                    client.value = l.user.client;
                    client.value['area_name'] = l.user.complement.area.name;
                    client.value['province_name'] = l.user.complement.province.name;
                    client.value['selling_method_name'] = l.user.complement.selling_method.name;

                    orders.value = l.orders;
                    maintenances.value = l.maintenances;
                    problems.value = l.problems;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getCallCenters();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getCallCenters();
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

        return {user,client, orders, maintenances, problems, dateFormat, date_now, loading, permission, getCallCenters, search};

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

.custom-modal .close span {
    top: 0 !important;
}


</style>
