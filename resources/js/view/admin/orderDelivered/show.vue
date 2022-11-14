<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.showOrderDelivered') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexOrderDelivered'}">
                                    {{ $t('global.orderDelivered') }}
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
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body pt-0">
                                        <div class="card-header mb-4">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>{{$t('global.InvoiceNumber')}} : {{order.id}}</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5>{{$t('global.DateOrder')}} : {{dateFormat(order.created_at)}}</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5>{{$t('global.store')}} : {{ store_name }}</h5>
                                                </div>

                                                <div class="col-md-4">
                                                    <h5>
                                                        {{$t('global.TotalPriceBeforeDiscount')}} :
                                                        {{order.sub_total }}
                                                        {{ order.currency }}
                                                    </h5>
                                                </div>

                                                <div class="col-md-4">
                                                    <h5>{{$t('global.discountValue')}} : {{ parseInt(order.is_online) == 1 ? order.discount : order.discount_offer }} {{ order.currency }}</h5>
                                                </div>

                                                <div class="col-md-4" v-if="order.order_other_offer">
                                                    <h5 v-if="order.order_other_offer">
                                                        {{$t('global.otherDiscount')}} :
                                                        {{ order.order_other_offer.offer }}
                                                        {{ order.currency }}
                                                    </h5>
                                                </div>

                                                <div class="col-md-4">
                                                    <h5>
                                                        {{$t('global.TotalPriceAfterDiscount')}} :
                                                        {{
                                                            order.order_other_offer?
                                                                parseFloat( order.sub_total - order.order_other_offer.offer - (parseInt(order.is_online) == 1 ? order.discount : order.discount_offer)).toFixed(2) :
                                                                parseFloat( order.sub_total - (parseInt(order.is_online) == 1 ? order.discount : order.discount_offer)).toFixed(2)
                                                        }}
                                                        {{ order.currency }}
                                                    </h5>
                                                </div>

                                                <div class="col-md-4" v-if="order.tax">
                                                    <h5 v-if="order.tax">{{$t('global.taxValue')}} : {{ order.tax }} {{ order.currency }}</h5>
                                                </div>

                                                <div class="col-md-4" v-if="order.tax">
                                                    <h5 v-if="order.tax">{{$t('global.TotalPriceAfterTax')}} : {{ parseFloat(order.total- order.shippingPrice) }} {{ order.currency }}</h5>
                                                </div>

                                                <div class="col-md-4" v-if="order.shippingPrice">
                                                    <h5 v-if="order.shippingPrice">{{$t('global.shipping')}} : {{ order.shippingPrice }} {{ order.currency }}</h5>
                                                </div>

                                                <div class="col-md-4">
                                                    <h5>{{$t('global.TotalPriceAfterDiscountAndShipping')}} : {{ order.total }} {{ order.currency }}</h5>
                                                </div>

                                            </div>

                                        </div>
                                        <table class="table table-center table-hover mb-0 datatable">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ $t('global.Products') }}</th>
                                                <th>{{ $t('global.full') }}</th>
                                                <th>{{ $t('global.partial') }}</th>
                                                <th>{{ $t('global.fullPrice') }}</th>
                                                <th>{{ $t('global.partialPrice') }}</th>
                                                <th>{{ $t('global.TotalPrice') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody v-if="order_details.length">
                                            <tr v-for="(it,index) in order_details" :key="it.id">
                                                <td>{{ index +1}}</td>
                                                <td>{{ it.product.name }}</td>
                                                <td>{{ it.quantity }} ( {{it.main_measurement_unit.name}} )</td>
                                                <td>{{ it.sub_quantity }} ( {{it.sub_measurement_unit.name}} )</td>
                                                <td>{{ it.sub_quantity ? it.price : '-'}}</td>
                                                <td>{{  it.sub_quantity ? it.sub_price : '-'}}</td>
                                                <td>
                                                    {{
                                                        parseFloat((it.quantity * it.price) + (it.sub_quantity * it.sub_price))
                                                            .toFixed(2)
                                                    }}
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tbody  v-else>
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
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let date_now = new Date().toISOString().split('T')[0];
        let user = ref({});
        let client = ref({});
        let order = ref({});
        let store_name = ref({});
        let order_details = ref([]);

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProducts = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/orderDelivered/${id.value}`)
                .then((res) => {
                    let l = res.data.data;
                    user.value = l.order.user;
                    client.value = l.order.user.client;
                    order.value = l.order;
                    store_name.value = l.order.store.name;
                    order_details.value = l.order.order_details;
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

        return {dateFormat,store_name,user,client,order,order_details, date_now, loading, permission, getProducts, search};

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
