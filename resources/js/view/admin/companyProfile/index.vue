<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.companyProfile') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.companyProfile') }}</li>
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
                                            v-if="permission.includes('companyProfile create')"
                                            :to="{name: 'createCompanyProfile'}"
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
                                            <th>{{ $t('global.Address') }}</th>
                                            <th>{{ $t('global.Phone') }}</th>
                                            <th>{{ $t('global.taxCard') }}</th>
                                            <th>{{ $t('global.commercialRecord') }}</th>
                                            <th>{{ $t('global.commercialRecordImage') }}</th>
                                            <th>{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="companyProfiles.length">
                                        <tr v-for="(item,index) in companyProfiles" :key="item.id">
                                            <td>{{ item.id }}</td>
                                            <td>{{ item.name ? item.name : '---' }}</td>
                                            <td>{{ item.phone ? item.phone : '---' }}</td>
                                            <td>{{ item.address ? item.address : '---' }}</td>
                                            <td>{{ item.tax_card ? item.tax_card : '---' }}</td>
                                            <td>{{ item.commercial_record ? item.commercial_record : '---' }}</td>
                                            <td>
                                                <img
                                                    :src="'/upload/companyProfile/' + item.media.file_name"
                                                    :alt="item.name"
                                                    class="custom-img"
                                                />
                                            </td>
                                            <td>
                                                <router-link
                                                    :to="{name: 'editCompanyProfile',params:{id:item.id}}"
                                                    v-if="permission.includes('companyProfile edit')"
                                                    class="btn btn-sm btn-success me-2"
                                                >
                                                    <i class="far fa-edit"></i>
                                                </router-link>
                                                <a href="#" @click="deleteCompanyProfile(item.id,index)"
                                                    v-if="permission.includes('companyProfile delete')"
                                                    data-bs-target="#staticBackdrop"
                                                    class="btn btn-sm btn-danger me-2"
                                                >
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="5">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination
                :data="companyProfilesPaginate"
                @pagination-change-page="getCompanyProfile"
                :limit="2"
            >
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
        // get packages
        let companyProfiles = ref([]);
        let companyProfilesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        const {t} = useI18n({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getCompanyProfile = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/companyProfile?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    companyProfilesPaginate.value = l.companyProfiles;
                    companyProfiles.value = l.companyProfiles.data;
                    console.log(l.companyProfiles);
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getCompanyProfile();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getCompanyProfile();
            }
        });


        function deleteCompanyProfile(id, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')} `,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/companyProfile/${id}`)
                    .then((res) => {
                        companyProfiles.value.splice(index, 1);
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

        return {companyProfiles, loading,permission, getCompanyProfile, search, deleteCompanyProfile, companyProfilesPaginate};

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
