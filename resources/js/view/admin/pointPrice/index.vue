<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t(`global.pointPrice`) }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t(`global.pointPrice`) }}</li>
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
                                        {{ $t('global.Search') }} :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end" v-if="!pointPrices.length">
                                        <router-link
                                            v-if="permission.includes('pointPrice create')"
                                            :to="{name: 'createPointPrice'}"
                                            class="btn btn-custom btn-warning"
                                        >
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t('global.Value') }}</th>
                                            <th class="text-center">{{ $t('global.Expire Date') }}</th>
                                            <th class="text-center">{{ $t('global.Status') }}</th>
                                            <th class="text-center">{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="pointPrices.length">
                                        <tr v-for="(item,index) in pointPrices" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.value }}</td>
                                            <td class="text-center">{{ item.expire_date != '' ? dateFormat(item.expire_date) : '-' }}</td>
                                            <td class="text-center">
                                                <a href="#" @click="activationPointPrice(item.id,item.status,index)">
                                                    <span :class="[parseInt(item.status) ? 'text-success hover': 'text-danger hover']">
                                                        {{ parseInt(item.status) ? $t('global.Active') : $t('global.Inactive') }}
                                                    </span>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <router-link
                                                    :to="{name: 'editPointPrice',params:{id:item.id}}"
                                                    v-if="permission.includes('pointPrice edit')"
                                                    class="btn btn-sm btn-success me-2"
                                                >
                                                    <i class="far fa-edit"></i>
                                                </router-link>
                                                <a href="#" @click="deletePointPrice(item.id,index)"
                                                    v-if="permission.includes('pointPrice delete')"
                                                    data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2"
                                                >
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
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
            <Pagination :limit="2" :data="pointPricesPaginate" @pagination-change-page="getPointPrice">
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
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {

        // get packages
        let pointPrices = ref([]);
        let pointPricesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        const {t} = useI18n({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getPointPrice = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/pointPrice?page=${page}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                pointPricesPaginate.value = l.pointPrices;
                pointPrices.value = l.pointPrices.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getPointPrice();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getPointPrice();
            }
        });

        let dateFormat = (item) => {
            return new Date(item).toDateString();
        };

        function deletePointPrice(id, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            })
            .then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/pointPrice/${id}`)
                    .then((res) => {
                        pointPrices.value.splice(index, 1);
                        Swal.fire({
                            icon: 'success',
                            title: `${t("global.DeletedSuccessfully")}`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                            text: `${t('global.YouCanNotDelete')}`,
                        });
                    });
                }
            });
        }

        function activationPointPrice(id, active,index) {
            Swal.fire({
                title: `${parseInt(active) ? t('global.AreYouSureInactive') :t('global.AreYouSureActive')} `,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    adminApi.get(`/v1/dashboard/activationPointPrice/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: `${parseInt(active) ? t('global.InactiveSuccessfully') :t('global.ActiveSuccessfully')}`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        pointPrices.value[index]['status'] =  parseInt(active) ? 0:1
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: 'error',
                            title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                            text: `${t('global.YouCanNotDelete')}`,
                        });
                    });
                }
            });
        }

        return {dateFormat,pointPrices, loading,permission, getPointPrice, search, deletePointPrice, activationPointPrice, pointPricesPaginate};

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
