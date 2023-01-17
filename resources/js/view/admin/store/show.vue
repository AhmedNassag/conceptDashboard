<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.showProductStore') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexStore'}">
                                    {{ $t('store.store') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.showProductStore') }}</li>
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
                                        بحث :
                                        <input type="search" v-model="search" class="custom"/>
                                    </div>

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ $t('global.barcode') }}</th>
                                            <th class="text-center">{{ $t('global.Name') }}</th>
                                            <th class="text-center">{{ $t('global.company') }}</th>
                                            <th class="text-center">{{ $t('global.category') }}</th>
                                            <th class="text-center">{{ $t('global.subCategory') }}</th>
                                            <th class="text-center">{{$t('global.Notes')}}</th>
                                            <th class="text-center">{{ $t('global.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="products.length">
                                        <tr v-for="(item) in products" :key="item.id">
                                            <td class="text-center"> <span v-if="item.error || item.quantity < item.Re_order_limit" class="fas fa-exclamation-circle red-text" aria-hidden="true"></span> {{ item.id}}</td>                                        <td>{{ item.barcode }}</td>
                                            <td class="text-center">{{ item.name }}</td>
                                            <td class="text-center">{{ item.company ? item.company.name : '---' }}</td>
                                            <td class="text-center">{{ item.category ? item.category.name : '---' }}</td>
                                            <td class="text-center">{{ item.sub_category ? item.sub_category.name : '---' }}</td>
                                            <td class="text-center danger" v-if="item.error"> {{$t('global.Expired')}} </td>
                                            <td class="text-center wrong" v-else-if="item.quantity < item.Re_order_limit"> {{$t('global.QuantityIsLow')}} </td>
                                            <td class="text-center great" v-else-if="item.quantity > item.maximum_product"> {{$t('global.QuantityIsBig')}} </td>
                                            <td class="text-center" v-else > {{$t('global.ThereIsNo')}} </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);"
                                                class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                :data-bs-target="'#edit-category-'+item.id">
                                                    <i class="fas fa-book-open"></i> {{$t('global.ShowSalesMovement')}}
                                                </a>
                                            </td>




                                            <!-- Edit Modal -->
                                            <div class="modal fade custom-modal" :id="'edit-category-'+item.id">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" id="print">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                {{ $t('global.ProductDetailsInStore') }}</h4>
                                                            <button :id="'close'"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                                <span>&times;</span></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body row" >

                                                            <div class="card bg-white projects-card">
                                                                <div class="card-body pt-0">
                                                                    <div class="tab-content pt-0">
                                                                        <div role="tabpanel" class="tab-pane fade active show">
                                                                            <loader v-if="loading"/>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-center table-hover mb-0 datatable">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th class="text-center">{{ $t('global.productStatus') }}</th>
                                                                                        <th class="text-center">{{ $t('global.Quantity') }} ({{item.main_measurement_unit.name}})</th>
                                                                                        <th class="text-center">{{ $t('global.count') }} ({{item.sub_measurement_unit.name}}) {{ $t('global.in') }} ({{item.main_measurement_unit.name}})</th>
                                                                                        <th class="text-center">{{ $t('global.Quantity') }} ({{item.sub_measurement_unit.name}})</th>
                                                                                        <th class="text-center">{{ $t('global.productionDate') }}</th>
                                                                                        <th class="text-center">{{ $t('global.expiryDate') }}</th>
                                                                                        <th class="text-center">{{$t('global.Notes')}}</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody v-if="item.store_products">
                                                                                        <tr v-for="(it,index) in item.store_products" :key="it.id">
                                                                                            <td class="text-center">{{ index +1}}</td>
                                                                                            <td class="text-center">{{ it.product_status.name }}</td>
                                                                                            <td class="text-center">{{ it.main_quantity }}</td>
                                                                                            <td class="text-center">{{ it.count_unit }}</td>
                                                                                            <td class="text-center">{{ it.sub_quantity_order }}</td>
                                                                                            <!-- <td>{{ it.count_unit > 1 ? it.sub_quantity_order : '---' }}</td> -->
                                                                                            <td class="text-center">{{ it.production_date ? it.production_date : '---' }}</td>                                                                                     <td>{{ it.expiry_date ?? '---' }}</td>
                                                                                            <td class="text-center danger" v-if="it.expiry_date < date_now"> {{$t('global.Expired')}} </td>
                                                                                            <td class="text-center" v-else> {{$t('global.ThereIsNo')}} </td>
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

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Edit Modal -->

                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <th class="text-center" colspan="9">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :limit="2" :data="productsPaginate" @pagination-change-page="getProducts">
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
import {onMounted, watch, ref, computed, toRefs} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";

export default {
    name: "index",
    props:["id"],
    setup(props) {

        const {id} = toRefs(props);
        // get packages
        let products = ref([]);
        let productsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let date_now = new Date().toISOString().split('T')[0];

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getProducts = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/store/${id.value}?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    productsPaginate.value = l.products;
                    let date = new Date().toISOString().split('T')[0];
                    l.products.data.forEach(el=>{
                        el['error'] = 0 ;
                        el['quantity'] = 0 ;
                        el.store_products.filter(el => el.sub_quantity_order > 0);
                        el.store_products.forEach(elm=>{
                            if (elm.expiry_date < date){
                                el['error'] = 1;
                            }else if (el['error'] == 0){
                                el['error'] = 0;
                            }
                            el['quantity'] += elm.main_quantity;
                        })
                    })
                    products.value = l.products.data;
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


        return {dateFormat,date_now,products, loading,permission, getProducts, search, productsPaginate};

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
.custom-img {
    width: 100px;
}

.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}
.danger{
    background-color: red;
    font-weight: 500;
    text-align: center;
}
.wrong{
    background: #fcb00c;
    font-weight: 500;
    text-align: center;
}
.great{
    background: greenyellow;
    font-weight: 500;
    text-align: center;
}

</style>
