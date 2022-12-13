<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.Target') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexfollowTargetPlan'}">
                                    {{ $t('global.CommissionPlanManagement') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Target') }}</li>
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
                                        <router-link v-if="permission.includes('followtargetPlan create')" :to="{name: 'createfollowTarget', params: {id}}" class="btn btn-custom btn-warning">
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
                                        <th>{{ $t('global.From') }}</th>
                                        <th>{{ $t('global.To') }}</th>
                                        <th v-if="id == 1 || id == 2 || id == 3 || id == 4">{{ $t('global.Amount') }}</th>
                                        <th v-if="id >4">{{ $t('global.Percentage') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="targets.length">
                                    <tr v-for="(item,index) in targets" :key="item.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.seller_category.name }}</td>
                                        <td>{{ item.from }}</td>
                                        <td>{{ item.to }}</td>
                                        <td v-if="id == 1 || id == 2 || id == 3 || id == 4">{{ item.amount }}</td>
                                        <td v-if="id > 4">{{ item.percent }}</td>

                                        <td>
                                            <router-link v-if="permission.includes('followtargetPlan edit')" :to="{name: 'editfollowTarget', params: {idPlan:id,idTarget:item.id}}" class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>
                                            <a href="#" @click="deleteTarget(item.id,index)" v-if="permission.includes('followtargetPlan delete')" data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
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
            <Pagination :data="targetPaginate" @pagination-change-page="getTarget">
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
import {onMounted, inject, watch, ref, toRefs, computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {

    name: "index",
    props:["id"],

    setup(props) {

        const emitter = inject('emitter');
        const {id} = toRefs(props)
        const {t} = useI18n({});

        let targets = ref([]);
        let targetPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);

        let getTarget = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/followtarget/${id.value}?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    console.log(l);
                    targetPaginate.value = l.targetPlan;
                    targets.value = l.targetPlan.data;
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

        function deleteTarget(id, index) {
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

                    adminApi.delete(`/v1/dashboard/followtarget/${id}`)
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

        return {targets,deleteTarget,permission, loading, getTarget, search, targetPaginate,id};

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
.modal-dialog{
    max-width: 700px !important;
}
.hr-show{
    color: #0E67D0;
    height: 5px;
}
.show-price{
    display: flex;
    justify-content: center;
}
.show-price h5{
    margin: 0px 5px 0px 5px;
}
.custom-modal .close span{
 top: 0 !important;
}

</style>
