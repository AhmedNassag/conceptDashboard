<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('sidebar.lead') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexLead'}">
                                    {{ $t('sidebar.lead') }}
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

                                <h2>{{lead.name}} <i class="fas fa-certificate text-primary small" data-bs-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i></h2>

                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <i class="fas fa-phone"></i> <span>{{lead.phone}}</span>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fas fa-map-marker-alt"></i> {{lead.address}}
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="far fa-calendar-alt"></i> <span>{{dateFormat(lead.created_at)}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2 class="text-center">التعامل الذى تم مع العميل</h2>
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">اسم الموظف</th>
                                                    <th class="text-center">التعامل مع العميل</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="actions.length">
                                                <tr v-for="(item,index) in actions" :key="item.id">
                                                    <td class="text-center">{{ index + 1 }}</td>
                                                    <td class="text-center">{{ item.employee.user.name }}</td>
                                                    <td class="text-center">{{ item.action }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                            <tr>
                                                <th class="text-center" colspan="7">لا يوجد بيانات</th>
                                            </tr>
                                            </tbody>
                                        </table>
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
        let lead = ref({});
        let actions = ref({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/lead/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    lead.value = l.lead;
                    actions.value = l.actions;
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

        return {dateFormat,lead,actions, date_now, loading, permission, getProducts, search};

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
