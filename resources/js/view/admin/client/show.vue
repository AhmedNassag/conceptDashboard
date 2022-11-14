<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.showInformationClient') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexClient'}">
                                    {{ $t('global.clients') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.Show') }}</li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-body">

                            <router-link
                                :to="{name: 'editClient',params:{id}}"
                                v-if="permission.includes('category edit')"
                                class="btn btn-sm btn-success me-2 mr-3">
                                <i class="far fa-edit"></i> {{$t('global.updateUserData')}}
                            </router-link>

                            <a href="#" @click="activationClient(user.id,user.status)">
                                                <span :class="[parseInt(user.status) ? 'text-success hover': 'text-danger hover']">{{
                                                        parseInt(user.status) ? 'تفعيل' : 'ايقاف تفعيل' }}</span>
                            </a>

                            <div class="text-center mb-5">
                                <label class="avatar avatar-xxl profile-cover-avatar" >
                                    <img class="avatar-img" :src="user.image_path" alt="Profile Image">
                                </label>
                                <h2>{{user.name}} <i class="fas fa-certificate text-primary small" data-bs-toggle="tooltip"
                                                  data-placement="top" title="" data-original-title="Verified"></i></h2>

                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <i class="fas fa-phone"></i> <span>{{user.phone}}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fas fa-map-marker-alt"></i> {{client.address}}
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="far fa-calendar-alt"></i> <span>{{dateFormat(user.created_at)}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="card-title">{{$t('global.sellingMethod')}}</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p>
                                                    {{client.selling_method_name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="card-title">{{$t('global.Area')}}</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p>
                                                    {{client.area_name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="card-title">{{$t('global.province')}}</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p>
                                                    {{client.province_name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="card-header mb-4">
                                                <h5 class="card-title">{{$t('global.tradeName')}}</h5>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p>
                                                    {{client.trade_name}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, watch, ref, computed, toRefs} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    props: ["id"],
    setup(props) {

        const {id} = toRefs(props);
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let date_now = new Date().toISOString().split('T')[0];
        let user = ref({});
        let client = ref({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/client/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    user.value = l.user;
                    client.value = l.user.client;
                    client.value['area_name'] = l.user.client.area.name;
                    client.value['province_name'] = l.user.client.province.name;
                    client.value['selling_method_name'] = l.user.client.selling_method.name;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getProducts();
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getProducts();
            }
        });

        function activationClient(id, status) {
            Swal.fire({
                title: `${parseInt(status) ? 'هل انت متاكد من ايقاف التفعيل ؟' : 'هل انت متاكد من التفعيل  ؟'} `,
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.get(`/v1/dashboard/activationClient/${id}`)
                        .then((res) => {
                            Swal.fire({
                                icon: 'success',
                                title: `${parseInt(status) ? 'تم ايقاف التفعيل بنجاح' : 'تم التفعيل بنجاح'}`,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            user.value['status'] =  parseInt(status) ? 0:1
                        })
                        .catch((err) => {
                            Swal.fire({
                                icon: 'error',
                                title: `يوجد خطا`,
                                text: `يوجد خطا في النظام!`,
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
        };

        return {dateFormat,user,client,activationClient, date_now, loading, permission, getProducts, search};

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

.custom-modal .close span {
    top: 0 !important;
}


</style>
