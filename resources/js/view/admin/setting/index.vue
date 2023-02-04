<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('sidebar.setting') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('sidebar.setting') }}</li>
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
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>{{$t('global.showPrice')}}</th>
                                        <th>{{ $t('global.closeApp') }}</th>
                                        <th>{{ $t('global.Phone') }}</th>
                                        <th>{{ $t('global.hotLine') }}</th>
                                        <th>{{ $t('global.watsApp') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="jobs.length">
                                    <tr v-for="(item) in jobs" :key="item.id">
                                        <td>{{ parseInt(item.show_price) ? $t('global.Yeas') : $t('global.No')}}</td>
                                        <td>{{ parseInt(item.close) ? $t('global.Yeas') : $t('global.No')}}</td>
                                        <td>{{item.phone}}</td>
                                        <td>{{item.hotLine}}</td>
                                        <td>{{item.wats_app}}</td>
                                        <td>

                                            <router-link
                                                :to="{name: 'editSetting', params: {id:item.id}}"
                                                v-if="permission.includes('setting edit')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                             <th class="text-center" colspan="6">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2"  :data="jobsPaginate" @pagination-change-page="getJob">
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
import {useI18n} from 'vue-i18n';

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});

        let jobs = ref([]);
        let jobsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getJob = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/setting?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    jobsPaginate.value = l.setting;
                    jobs.value = l.setting.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getJob();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getJob();
            }
        });

        return {jobs, loading, getJob, search,permission, jobsPaginate};

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
