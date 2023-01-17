<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.categoryReport') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('sidebar.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.categoryReport') }}</li>
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
                                    <div class="col-12 row justify-content-end">
                                        <form @submit.prevent="getOrder" class="needs-validation">
                                            <div class="form-group row align-items-center">

                                                <div class="col-md-3">
                                                    <label >{{$t('global.FromDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="fromDate">
                                                </div>

                                                <div class="col-md-3">
                                                    <label >{{$t('global.ToDate')}}</label>
                                                    <input type="date" class="form-control date-input"
                                                           v-model="toDate">
                                                </div>

                                                <div class="col-md-3">
                                                    <label >{{$t('global.ChooseType')}}</label>
                                                    <select v-model="order"  class="form-control">
                                                        <option value="desc">{{$t('global.ascSale')}}</option>
                                                        <option value="asc">{{$t('global.descSale')}}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mt-4">
                                                    <button class="btn btn-primary" type="submit">{{$t('global.Search')}}</button>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <div class="col-md-3">
                                        <!--                                        {{ $t('global.Search') }}:-->
                                        <!--                                        <input type="search" v-model="search" class="custom"/>-->
                                    </div>
                                    <div class="col-md-2 row justify-content-end"></div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.category') }}</th>
                                        <th>{{ $t('global.subCategory') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="categories.length">
                                    <tr v-for="(item,index) in categories" :key="item.id">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ item.product.category.name }}</td>
                                        <td>{{ item.product.sub_category ? item.product.sub_category.name : '---' }}</td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else>
                                    <tr>
                                        <th class="text-center" colspan="3">{{ $t('global.NoDataFound') }}</th>
                                    </tr>
                                    </tbody>
                                </table>
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
import {onMounted, inject, watch, ref, computed} from "vue";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";
import {useStore} from "vuex";

export default {
    name: "index",
    setup() {
        const {t} = useI18n({});
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let categories = ref([]);
        let fromDate = ref('');
        let order = ref('');
        let toDate = ref('');
        let loading = ref(false);

        let getOrder = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/categoryReport?from_date=${fromDate.value}&to_date=${toDate.value}&order=${order.value}`)
                .then((res) => {

                    let l = res.data.data;
                    categories.value = l.categories;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });

        }

        onMounted(() => {
            getOrder();
        });


        return {categories,permission, loading,fromDate,toDate,getOrder,order};

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
    width: 100%;
}

.custom {
    border: 1px solid #D7D7D7;
    padding: 2px;
    border-radius: 5px;
}

.btn {
    color: #fff;
}
.hover:hover{
    border: 2px solid;
    padding: 3px;
    border-radius: 7px;
}


.amount{
    background-color: #fcb00c;
    color: #000;
    padding: 10px;
}
.amount h5{
    font-weight: 700;
    text-align: center;
}
.submit-margin{
    margin-top: 38px !important;
}
.date-input{
    width: 175px !important;
    display: inline-block !important;
    margin: 0px 8px 0 8px !important;
}

.head-table{
    background: #000;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}
.total-head{
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background-color: #fcb00c !important;
    border-radius: 10px;
}
.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}
.head-data-table{
    display: flex;
    width: 100%;
    justify-content: space-around;
}
.head-button{
    margin-top: 5px;
    margin-bottom: 5px;
}
.edit-envoice .modal-dialog{
    max-width: 500px !important;
}

/*======== print ===========*/

.head-data{
    margin: 0px 0 15px 0;
}
.image-div{
    display: flex;
    flex-direction: row-reverse;
}
.image-div img {
    width: 35%;
}
.invoice-head{
    display: flex;
    align-items: center;
}

td.description,
th.description {
    width: 150px;
    max-width: 150px;
}

td.index,
th.index {
    width: 50px;
    max-width: 50px;
}

td.quantity,
th.quantity {
    width: 50px;
    max-width: 50px;
    word-break: break-all;
}

td.price1,
th.price1 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
}

td.price2,
th.price2 {
    width: 55px;
    max-width: 55px;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    margin: auto;
    width: 300px;
    max-width: 300px;
}

img {
    max-width: inherit;
    width: inherit;
}


</style>
