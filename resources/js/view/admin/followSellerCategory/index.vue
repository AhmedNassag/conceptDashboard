<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.EmployeesTargets') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.EmployeesTargets') }}</li>
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
                                        <router-link v-if="permission.includes('followSellerCategory create')" :to="{name: 'createfollowSellerCategory', params: {lang: locale || 'ar'}}" class="btn btn-custom btn-warning">
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
                                            <th>{{ $t('global.Employee') }}</th>
                                            <th>{{ $t('global.Targets') }}</th>
                                            <th>{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="targets.length">
                                    <tr v-for="(item,index) in targets"  :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td><span v-for="category in item.followseller_categories" :key="category.id"> {{category.name}} <br> </span></td>
                                        <td>
                                            <router-link v-if="permission.includes('followSellerCategory edit')" :to="{name: 'editfollowSellerCategory', params: {lang: locale || 'ar',id:item.id}}" class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteTarget(item.id,item.user.name,index)" v-if="permission.includes('SellerCategory delete')" data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="4">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :data="targetsPaginate" @pagination-change-page="getTarget">
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
import {onMounted, inject, watch, ref, computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let targets = ref([]);
        let targetsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');

        let getTarget = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/followsellerCategory?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l);
                    targetsPaginate.value = l.employees;
                    targets.value = l.employees.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getTarget();
        });

        emitter.on('get_lang', () => {
            getTarget(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getTarget();
            }
        });


        function deleteTarget(id, incomeName, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')} (${incomeName})`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/followsellerCategory/${id}`)
                    .then((res) => {
                        targets.value.splice(index, 1);

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

        return {targets,permission, loading, getTarget, search, deleteTarget, targetsPaginate};

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
    color: #FFF;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}

</style>
