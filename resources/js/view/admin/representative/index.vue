<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.representative') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.representative') }}</li>
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
                                        {{ $t('global.Search') }}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            :to="{name: 'createRepresentative', params: {lang: locale || 'ar'}}"
                                            v-if="permission.includes('representative create')"
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
                                        <th>{{ $t('global.Phone') }}</th>
                                        <th>{{ $t('global.Email') }}</th>
                                        <th>{{ $t('global.HiringDate') }}</th>
                                        <th>{{ $t('global.areas') }}</th>
                                        <th>{{ $t('global.Status') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="representatives.length">
                                    <tr v-for="(item,index) in representatives" :key="item.id">
                                        <td>{{ item.user.id }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td>{{ item.user.phone }}</td>
                                        <td>{{ item.user.email }}</td>
                                        <td>{{ item.hiring_date }}</td>
                                        <td> <span  v-for="(it,index) in item.areas" :key="it.id">{{ it.name }} <br></span></td>
                                        <td>
                                            <a href="#" @click="activationRepresentative(item.id,item.user.name,item.user.status,index)">
                                                <span :class="[parseInt(item.user.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(item.user.status) ? $t('global.Active') : $t('global.Inactive')
                                                    }}</span>
                                            </a>
                                        </td>

                                        <td>

                                            <router-link
                                                :to="{name: 'editRepresentative', params: {id:item.id}}"
                                                v-if="permission.includes('representative edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <router-link
                                                :to="{name: 'changePasswordRepresentative', params: {id:item.id}}"
                                                v-if="permission.includes('representativeChangePassword edit')"
                                                class="btn btn-sm btn-blue me-2">
                                                <i class="fas fa-key"></i>
                                            </router-link>

                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                             <th class="text-center" colspan="9">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2" :data="representativesPaginate" @pagination-change-page="getEmployee">
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
import {onMounted, inject, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});

        let representatives = ref([]);
        let representativesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getEmployee = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/representative?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    representativesPaginate.value = l.representatives;
                    representatives.value = l.representatives.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getEmployee();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getEmployee();
            }
        });

        function activationRepresentative(id, employeeName, active,index) {
            Swal.fire({
                title: `${active ? t('global.AreYouSureInactive') :t('global.AreYouSureActive')}  (${employeeName})`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationRepresentative/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${active ? t('global.InactiveSuccessfully') :t('global.ActiveSuccessfully')}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            representatives.value[index]['user']['status'] =  active ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `${t('global.ThereIsAnErrorInTheSystem')}`,
                                text: `${t('global.YouCanNotModifyThisSafe')}`,
                            });
                        });
                }
            });
        }

        return {representatives, loading, getEmployee, search,permission, activationRepresentative, representativesPaginate};

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
