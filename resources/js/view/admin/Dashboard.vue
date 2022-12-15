<template>
<div :class="['page-wrapper','page-wrapper-ar']">
    <div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">الرئيسية</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active">الرئيسية</li>
                </ul>
            </div>

        </div>
    </div>
    <!-- /Page Header -->


        <div class="row">
        <div class="col-md-12">
            <loader v-if="loading"/>
            <!--/Wizard-->
            <div class="row">

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.prushaseInvoice") }}</p>
                            <p>
                                <router-link :to="{name:'createPurchaseInvoice'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.saleInvoice") }}</p>
                            <p>
                                <router-link :to="{name:'createOrderDirect'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.prushasereturn") }}</p>
                            <p>
                                <router-link :to="{name:'createPurchaseReturn'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.incomeReturn") }}</p>
                            <p>
                                <router-link :to="{name:'indexOrderReturned'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("sidebar.client") }}</p>
                            <h5>{{ client }}</h5>
                            <p>
                                <router-link :to="{name:'indexClient'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("sidebar.supplier") }}</p>
                            <h5>{{ supplier }}</h5>
                            <p>
                                <router-link :to="{name:'indexSupplier'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("sidebar.company") }}</p>
                            <h5>{{ company }}</h5>
                            <p>
                                <router-link :to="{name:'indexCompany'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("sidebar.category") }}</p>
                            <h5>{{ category }}</h5>
                            <p>
                                <router-link :to="{name:'indexCategory'}">
                                    {{ $t("global.viewDetails") }}
                                </router-link>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.orderSuccess") }}</p>
                            <h5>{{ orderSuccess }}</h5>
                            <p>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.orderFailedPartial") }}</p>
                            <h5>{{ orderFailedPartial }}</h5>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card wizard-card flex-fill">
                        <div class="card-body">
                            <p class="text-primary mt-0 mb-2">{{ $t("global.orderFailed") }}</p>
                            <h5>{{ orderFailed }}</h5>
                            <p>
                            </p>
                            <span :class="['dash-widget-icon bg-1','dash-widget-icon-ar']">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
            <!--/Wizard-->
            <div class="row">
                <div class="col-lg-12 d-flex">
                    <div class="card w-100">
                        <div class="card-body pt-0 pb-2">
                            <div class="card-header justify-content-between align-content-center">
                                <h5 class="card-title col-md-4">{{$t('global.Overview')}}</h5>
                                <div class="col-md-5 d-flex">
                                        <select class="form-control" v-model="year">
                                            <option value="" selected>{{$t('global.Choose')}}...</option>
                                            <option value="2022">2022</option>
                                            <option :value="`${2022 + y}`" v-for="y in [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]">
                                                {{ 2022 + y }}
                                            </option>
                                        </select>
                                        <select @change="changeOrder" class="form-control mx-1" v-model="valueOrder">
                                            <option value="quantity">الكمية</option>
                                            <option value="price" selected>السعر</option>
                                        </select>
                                </div>
                            </div>
                            <apexchart
                                type="line"
                                :options="chartOptions3"
                                :series="series"
                            >
                            </apexchart>
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
import {onBeforeMount, reactive, ref,watch} from "vue";
import adminApi from "../../api/adminAxios";
import VueApexCharts from "vue3-apexcharts";

export default {
    components:{
        apexchart: VueApexCharts,
    },
    setup(){

        const company = ref(null);
        const category = ref(null);
        const supplier = ref(null);
        const orderSuccess = ref(null);
        const orderFailedPartial = ref(null);
        const orderFailed = ref(null);
        const orderPrice = ref([]);
        const client = ref(null);
        const valueOrder = ref('quantity');
        const orderQauntity = ref([]);
        let chartOptions3 =  ref({
            chart: {
                height: 360,
                type: 'line',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ["#FF5B37","#fcb00c"],
            stroke: {
                curve: 'straight',
                width: [1]
            },
            markers: {
                size: 4,
                colors: ["#FF5B37","#fcb00c"],
                strokeColors: ["#FF5B37","#fcb00c"],
                strokeWidth: 1,
                hover: {
                    size: 7,
                }
            },
            grid: {
                position: 'front',
                borderColor: '#ddd',
                strokeDashArray: 7,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                lines: {
                    show: false,
                }
            },
            yaxis: {
                lines: {
                    show: true,
                }
            }

        });
        let series = ref([]);
        const loading = ref(false);
        let year = ref('');

        let getDashboard = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/getDashboard`)
                .then((res) => {
                    let l = res.data.data;
                    company.value = l.company;
                    client.value = l.client;
                    category.value =  l.category;
                    supplier.value = l.supplier;
                    orderSuccess.value = l.orderSuccess;
                    orderFailed.value = l.orderFailed;
                    orderFailedPartial.value = l.orderFailedPartial;

                    for (let i = 1;i <= 12;++i){
                        if(l.orderQauntity[i]){
                            orderQauntity.value.push(l.orderQauntity[i]);
                        }else{
                            orderQauntity.value.push(null);
                        }
                    }

                    for (let i = 1;i <= 12;++i){
                        if(l.orderPrice[i]){
                            orderPrice.value.push(l.orderPrice[i]);
                        }else{
                            orderPrice.value.push(null);
                        }
                    }

                    series.value = [
                        {
                            name: "Quantity",
                            data: [...orderQauntity.value]
                        }
                    ];

                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onBeforeMount(() => {
            getDashboard();
        });

        watch(year,() => {
            if(year.value){
                adminApi.get(`/v1/dashboard/getChart?year=${year.value}`)
                    .then((res) => {
                        let l = res.data.data;
                        orderQauntity.value = [];
                        orderPrice.value = [];
                        series.value = [];
                        for (let i = 1;i <= 12;++i){
                            if(l.orderQauntity[i]){
                                orderQauntity.value.push(l.orderQauntity[i]);
                            }else{
                                orderQauntity.value.push(null);
                            }
                        }

                        for (let i = 1;i <= 12;++i){
                            if(l.orderPrice[i]){
                                orderPrice.value.push(l.orderPrice[i]);
                            }else{
                                orderPrice.value.push(null);
                            }
                        }

                        if(valueOrder.value == 'quantity'){
                            series.value = [
                                {
                                    name: "Quantity",
                                    data: [...orderQauntity.value]
                                }
                            ];
                        }else {
                            series.value = [
                                {
                                    name: "Price",
                                    data: [...orderPrice.value]
                                }
                            ];
                        }

                    })
                    .catch((err) => {});
            }
        });

        let changeOrder = () => {
            series.value = [];

            if(valueOrder.value == 'quantity'){
                series.value = [
                    {
                        name: "Quantity",
                        data: [...orderQauntity.value]
                    }
                ];
            }else {
                series.value = [
                    {
                        name: "Price",
                        data: [...orderPrice.value]
                    }
                ];
            }
        }

        return {
            company,
            category,
            supplier,
            orderSuccess,
            orderFailedPartial,
            chartOptions3,
            loading,
            year,
            series,
            orderPrice,
            orderQauntity,
            valueOrder,
            changeOrder,
            client,
            orderFailed
        };
    }
}
</script>

<style scoped>

.bg-1 {
    background: #103C67  !important;
}

.bg-1 i {
    color: #fff !important;
}

.dash-widget-icon-ar{
    right: unset;
    left: -10px;
}

.card .card-header{
    display: flex;
}

</style>
