<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.termsPrivacy') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.termsPrivacy') }}</li>
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
                                    <div class="col-5 row justify-content-end" v-if="termsPrivacies.length"></div>
                                    <div class="col-5 row justify-content-end" v-else>
                                        <router-link
                                            v-if="permission.includes('termsPrivacy create')"
                                            :to="{name: 'createTermsPrivacy'}"
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
                                            <th class="text-center">{{ $t('global.Terms') }}</th>
                                            <th class="text-center">{{ $t('global.Privacy') }}</th>
                                            <th class="text-center">{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="termsPrivacies.length">
                                        <tr v-for="(item,index) in termsPrivacies" :key="item.id">
                                            <td class="text-center">{{ index + 1 }}</td>
                                            <td class="text-center">{{ item.term }}</td>
                                            <td class="text-center">{{ item.privacy }}</td>
                                            <td class="text-center">
                                                <router-link
                                                    :to="{name: 'editTermsPrivacy', params: {id:item.id}}"
                                                    v-if="permission.includes('termsPrivacy edit')"
                                                    class="btn btn-sm btn-success me-2">
                                                    <i class="far fa-edit"></i>
                                                </router-link>
                                                <a href="#" @click="deleteTermsPrivacy(item.id,item.name,index)"
                                                    v-if="permission.includes('termsPrivacy delete')"
                                                    data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </td>

                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="3">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2" :data="termsPrivaciesPaginate" @pagination-change-page="getTermsPrivacy">
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

        // get packages
        let termsPrivacies = ref([]);
        let termsPrivaciesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getTermsPrivacy = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/termsPrivacy?page=${page}&search=${search.value}`)
            .then((res) => {
                let l = res.data.data;
                termsPrivaciesPaginate.value = l.termsPrivacies;
                termsPrivacies.value = l.termsPrivacies.data;
            })
            .catch((err) => {
                console.log(err.response.data);
            })
            .finally(() => {
                loading.value = false;
            });
        }

        onMounted(() => {
            getTermsPrivacy();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getTermsPrivacy();
            }
        });


        function deleteTermsPrivacy(id, index) {
            Swal.fire({
                title: `${t('global.AreYouSureDelete')}`,
                text: `${t("global.YouWontBeAbleToRevertThis")}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/termsPrivacy/${id}`)
                        .then((res) => {
                            termsPrivacies.value.splice(index, 1);

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

        return {termsPrivacies, loading,permission, getTermsPrivacy, search, deleteTermsPrivacy, termsPrivaciesPaginate};

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
