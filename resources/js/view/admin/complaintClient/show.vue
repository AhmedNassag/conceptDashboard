<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.ShowComplaintClients') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexComplaintClient'}">
                                    {{ $t('global.ComplaintClients') }}
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
                            <div class="text-center mb-5">
                                <label class="avatar avatar-xxl profile-cover-avatar" >
                                    <img class="avatar-img" :src="user.image_path" alt="Profile Image">
                                </label>
                                <h2>
                                    {{user.name}}
                                    <i class="fas fa-certificate text-primary small" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i>
                                </h2>
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
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body pt-0">
                                        <div class="card-header mb-4">
                                            <h5 class="card-title">{{complaint_name}}</h5>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p>
                                                {{complaint.notes}}
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
        // get packages
        let complaint = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let date_now = new Date().toISOString().split('T')[0];
        let user = ref({});
        let client = ref({});
        let complaint_name = ref({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/complaintClient/${id.value}`)
            .then((res) => {
                let l = res.data.data;
                user.value = l.complaint.user;
                client.value = l.complaint.user.client;
                complaint_name.value = l.complaint.complaint.name;
                complaint.value = l.complaint;
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

        return {dateFormat,user,client,complaint_name, date_now, complaint, loading, permission, getProducts, search};

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
