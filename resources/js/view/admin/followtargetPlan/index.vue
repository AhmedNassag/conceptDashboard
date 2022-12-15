<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.followCommissionPlanManagement') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.followCommissionPlanManagement') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->

            <!-- Table -->
            <div class="row">
                <div class="col-md-6" v-for="item in targets" :key="item.id">
                    <div class="card">
                        <router-link v-if="permission.includes('followtargetPlan read')" :to="{name: 'indexfollowTarget', params: {lang: locale || 'ar',id:item.id}}" class="btn btn-sm btn-category">
                            <h3>{{item.name}}</h3>
                        </router-link>
                    </div>
                </div>
            </div>
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
        let loading = ref(false);
        const search = ref('');

        let getTarget = (page = 1) => {
            loading.value = true;

                adminApi.get(`/v1/dashboard/followtargetPlan?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    targets.value = l.targetPlan;
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

                    adminApi.delete(`/v1/dashboard/followtargetPlan/${id}`)
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

        let dateFormat = (item) => {
            return new Date(item).toDateString();
        }

        return {targets, loading,permission, getTarget,dateFormat, search, deleteTarget};

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
.btn-category{
    background-color: #0E67D0;
    border: 1px solid #0E67D0;
}
.btn-category:hover{
    background-color: #00DD2F;
    border: 1px solid #0E67D0;
}
.btn-category:hover h3{
    color: black;
}

</style>
