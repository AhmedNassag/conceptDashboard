<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$t('global.TransferringTreasury')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{$t('global.TransferringTreasury')}}</li>
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
                                        {{$t('treasury.Search')}}:
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>
                                    <div class="col-5 row justify-content-end">
                                        <router-link
                                            v-if="permission.includes('transferringTreasury create')"
                                            :to="{name: 'createTransferringTreasury', params: {lang: locale || 'ar'}}"
                                            class="btn btn-custom btn-warning">
                                            {{$t('treasury.Add')}}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{$t('global.TransferringForTreasury')}}</th>
                                        <th>{{$t('global.TransferringToTreasury')}}</th>
                                        <th>{{ $t('global.AmountTransfer') }}</th>
                                        <th>{{$t('global.RemainingAmountInTreasury')}}</th>
                                        <th>{{$t('global.Date')}}</th>
                                        <th>{{ $t('global.ProcessWriter') }}</th>
                                        <th>{{$t('global.Notes')}}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="treasuries.length">
                                    <tr v-for="(item,index) in treasuries" :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.from_treasury.name }}</td>
                                        <td>{{ item.to_treasury.name }}</td>
                                        <td>{{ item.amount }}</td>
                                        <td>{{ item.from_treasury.amount }}</td>
                                        <td>
                                            {{ dateFormat(item.created_at) }}
                                        </td>
                                        <td>
                                            {{ item.user.name }}
                                        </td>
                                        <td>
                                            {{ item.notes }}
                                        </td>
                                        <td>
                                            <router-link
                                                v-if="permission.includes('transferringTreasury edit')"
                                                :to="{name: 'editTransferringTreasury', params: {lang: locale || 'ar',id:item.id}}"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteTreasury(item.id,index)"
                                               v-if="permission.includes('transferringTreasury delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                             <th class="text-center" colspan="9">{{$t('treasury.NoDataFound')}}</th>
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
            <Pagination :limit="2" :data="treasuriesPaginate" @pagination-change-page="getTreasuries">
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

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');

        // get packages
        let treasuries = ref([]);
        let treasuriesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getTreasuries = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/transferringTreasury?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    treasuriesPaginate.value = l.treasuries;
                    treasuries.value = l.treasuries.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getTreasuries();
        });

        emitter.on('get_lang', () => {
            getTreasuries(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getTreasuries();
            }
        });

        function deleteTreasury(id, index) {
            Swal.fire({
                title: `Are you sure delete ?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/transferringTreasury/${id}`)
                        .then((res) => {
                            treasuries.value.splice(index, 1);

                            Swal.fire({
                                icon: 'success',
                                title: 'Your package has been deleted.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: 'يوجد خطا في النظام...',
                                text: 'لا تسنطيع حذف هذه الحزمة !',
                            });
                        });
                }
            });
        }

        let dateFormat = (item) => {
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        }

        return {treasuries, loading,deleteTreasury,permission, getTreasuries,dateFormat, search, treasuriesPaginate};

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

</style>
