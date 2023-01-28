<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">المحفظة</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">نقاط المستخدمين</li>
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

                                        <!-- <router-link
                                            v-if="permission.includes('share create')"
                                           :to="{name: 'createShare'}"
                                            class="btn btn-custom btn-warning">
                                            اضافه
                                        </router-link> -->
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">الإسم</th>
                                            <th class="text-center">نقاط المنتجات</th>
                                            <th class="text-center">نقاط الترحيب</th>
                                            <th class="text-center">إجمالى النقاط</th>
                                            <th class="text-center">الإجمالى بالنقدية</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="wallets.length">
                                        <tr v-for="(item,index) in wallets" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.user.name }}</td>
                                            <td class="text-center">{{ item.product_points ? item.product_points : 0 }}</td>
                                            <td class="text-center">{{ item.welcome_points ? item.welcome_points : 0 }}</td>
                                            <td class="text-center">{{ item.product_points + item.welcome_points }}</td>
                                            <td class="text-center">{{ (item.product_points + item.welcome_points) * (value[0].value)}}</td>
                                            <td class="text-center">
                                                <!-- <a v-if="parseInt(item.points) >= parseInt(item.competition.count)" href="javascript:void(0);"
                                                   class="btn btn-sm btn-success me-2">
                                                    <i class="fas fa-check-circle"></i>
                                                    تم تحقيق الهدف
                                                </a>
                                                <a v-else href="javascript:void(0);"
                                                   class="btn btn-sm btn-danger me-2">
                                                    <i class="fa fa-xmark-circle"></i>
                                                    لم يتم تحقيق الهدف
                                                </a> -->
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
            <Pagination :limit="2" :data="walletsPaginate" @pagination-change-page="getWallet">
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
        let wallets = ref([]);
        let value = ref([]);
        let walletsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getWallet = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/wallet?page=${page}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                walletsPaginate.value = l.wallets;
                wallets.value = l.wallets.data;
                value.value = l.value.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getWallet();
        });


        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getWallet();
            }
        });


        return {getWallet, loading,permission, search, walletsPaginate, wallets, value};

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
