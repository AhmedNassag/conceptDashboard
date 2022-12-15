<template>
    <div :class="['page-wrapper','page-wrapper-ar']">
        <div class="content container-fluid">
            <notifications :position="'top left'"  />
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('sidebar.leadClient') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard'}">
                                    الرئيسية
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('sidebar.leadClient') }}</li>
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
                                    <div class="col-4">
<!--                                        بحث :-->
<!--                                        <input type="search" v-model="search" class="custom"/>-->
                                    </div>
                                    <div class="col-8 row justify-content-end">
                                        <a
                                            href="javascript:void(0)"
                                            @click.prevent="getClient"
                                            class="btn btn-custom btn-info">
                                            اضافه 10 عملاء
                                        </a>
                                        <router-link
                                            v-if="permission.includes('category create')"
                                            :to="{name: 'createLeadClient'}"
                                            class="btn btn-custom btn-warning">
                                            اضافه
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>رقم الهاتف</th>
                                        <th>العنوان</th>
                                        <th>نوعه</th>
                                        <th>الاجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="leadClients.length">
                                    <tr v-for="(item,index) in leadClients"  :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.phone }}</td>
                                        <td>{{ item.address }}</td>
                                        <td>{{ item.type }}</td>
                                        <td>
<!--                                            <a href="javascript:void(0);"-->
<!--                                               v-if="!item.lead_client"-->
<!--                                               class="btn btn-sm btn-info me-2"-->
<!--                                               data-bs-toggle="modal"-->
<!--                                               :data-bs-target="'#edit-category'+item.id"-->
<!--                                            >-->
<!--                                                <i class="fas fa-book-open"></i> {{$t('global.Show')}}-->
<!--                                            </a>-->
                                            <router-link
                                                :to="{name: 'addActionLeadClient',params:{id:item.id}}"
                                                :title="$t('global.AddAction')"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i> {{$t('global.AddAction')}}
                                            </router-link>
                                            <router-link
                                                :to="{name: 'changeLeadClient',params:{id:item.id}}"
                                                :title="$t('global.changeLead')"
                                                class="btn btn-sm btn-info me-2">
                                                <i class="fa fa-check"></i> {{$t('global.changeLead')}}
                                            </router-link>
<!--                                            <a class="btn btn-sm btn-info me-2" href="#" @click="changeLeadClient(item.id,index)">{{$t('global.changeLead')}}</a>-->
                                        </td>

                                        <!-- invoice big Modal -->
                                        <div class="modal fade custom-modal" :id="'edit-category'+item.id" v-if="!item.lead_client">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            التفاصيل
                                                        </h4>
                                                        <button :id="'close-'+item.id"  type="button" class="close print-button" data-bs-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body row" >

                                                        <div class="card bg-white projects-card">
                                                            <div class="card-body pt-0">
                                                                <div class="tab-content pt-0">
                                                                    <div role="tabpanel" :id="'tab-4'" class="tab-pane fade active show">
                                                                        <div class="row">
                                                                            <form  class="needs-validation">
                                                                                <div class="form-row row">

                                                                                    <div class="col-md-4 mb-3">
                                                                                        <label>متابعه العملاء</label>
                                                                                        <select  v-model="data.lead_follow_up_id" class="form-select" :class="{'is-invalid':v$.lead_follow_up_id.$error,'is-valid':!v$.lead_follow_up_id.$invalid}">
                                                                                            <option v-for="orderSt in leadFollows" :kay="orderSt.id" :value="orderSt.id">{{orderSt.name}}</option>
                                                                                        </select>

                                                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                        <div class="invalid-feedback">
                                                                                            <span v-if="v$.lead_follow_up_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-4 mb-3">
                                                                                        <label>مصدر العملاء</label>
                                                                                        <select  v-model="data.lead_Sources_id" class="form-select" :class="{'is-invalid':v$.lead_Sources_id.$error,'is-valid':!v$.lead_Sources_id.$invalid}">
                                                                                            <option v-for="orderSt in leadSources" :kay="orderSt.id" :value="orderSt.id">{{orderSt.name}}</option>
                                                                                        </select>

                                                                                        <div class="valid-feedback">{{$t('global.LooksGood')}}</div>
                                                                                        <div class="invalid-feedback">
                                                                                            <span v-if="v$.lead_Sources_id.required.$invalid">{{$t('global.TreasuryIsRequired')}}<br /> </span>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-md-4 mb-3 position-relative">
                                                                                        <label> اختار المنتح </label>
                                                                                        <label class="balance"> {{$t('global.Balance')}}}</label>

                                                                                        <input type="text"
                                                                                               class="form-control mb-1 input-Sender"
                                                                                               v-model="searchProduct"
                                                                                               @input="searchSender"
                                                                                               @blur="isButton = true"
                                                                                               @focus="searchSender"
                                                                                               autofocus
                                                                                               :placeholder="$t('treasury.Search')"
                                                                                        >
                                                                                        <ul class="dropdown-search sender-search" v-if="dropDownSenders.length">
                                                                                            <li
                                                                                                class="Sender"
                                                                                                v-for="(dropDownSender,index) in dropDownSenders"
                                                                                                :key="index"
                                                                                                @click="showSenderName(index)"
                                                                                                @mouseenter="senderHover"
                                                                                            >
                                                                                                {{ dropDownSender.name }}
                                                                                            </li>
                                                                                        </ul>

                                                                                        <input type="text"
                                                                                               disabled
                                                                                               v-model="nameSender"
                                                                                        >
                                                                                    </div>

                                                                                </div>
                                                                            </form>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /invoice big Modal-->
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
            <!-- /Table -->
            <!-- start Pagination -->
            <Pagination :limit="2" :data="leadClientsPaginate" @pagination-change-page="getLeadClients">
                <template #prev-nav>
                    <span>&lt; السابق</span>
                </template>
                <template #next-nav>
                    <span>التالي &gt;</span>
                </template>
            </Pagination>
            <!-- end Pagination -->
        </div>
        <!-- /Page Wrapper -->
    </div>
</template>

<script>
import {onMounted, watch, ref, computed, reactive, toRefs} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {minLength, numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import {notify} from "@kyvg/vue3-notification";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {

        // get packages
        let leadClients = ref([]);
        let leadClientsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        const searchProduct = ref('');
        let store = useStore();
        let nameSender = ref('');
        let dropDownSenders = ref([]);
        let isButton = ref(true);
        let leadFollows = ref([]);
        let leadSources = ref([]);
        let products = ref([]);
        const {t} = useI18n({});

        let permission = computed(() => store.getters['authAdmin/permission']);

        let getClient = () => {
            if(leadClients.value.length <= 10){
                loading.value = true;

                adminApi.get(`/v1/dashboard/leadClient`)
                    .then((res) => {
                        getLeadClients();
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
                    .finally(() => {
                        loading.value = false;
                    });
            }
        };

        let getLeadClients = () => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/leadClientGet`)
                .then((res) => {
                    let l = res.data.data;
                    leadClients.value = l.leadClient.data;
                    leadClientsPaginate.value = l.leadClient;
                    leadFollows.value  = l.leadFollows;
                    leadSources.value = l.leadSources;
                })
                .catch((err) => {
                    console.log(err.response);
                })
                .finally(() => {
                    loading.value = false;
                });
        };

        onMounted(() => {
            getLeadClients();
        });

        function deleteMeasure(id, index) {
            Swal.fire({
                title: `هل تريد هذف هذا العنصر ؟ `,
                text: `لن تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.delete(`/v1/dashboard/lead/${id}`)
                        .then((res) => {
                            clients.value.splice(index,  1);

                            Swal.fire({
                                icon: 'success',
                                title: `تم الحذف بنجاح`,
                                showConfirmButton: false,
                                timer: 1500
                            });
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
        };

        let changeStatus =  reactive({
            data:{
                lead_follow_up_id: null,
                lead_Sources_id: null,
                product_id: null,
                note: ''
            },
        });

        let searchSender = () => {
            dropDownSenders.value = [];
            if(searchProduct.value){
                let thisString = new RegExp(searchProduct.value,'i');
                let items = products.value.filter(e => e.name.match(thisString));
                dropDownSenders.value = items.splice(0,10);
            }else{
                dropDownSenders.value = [];
            }
            isButton.value = false;
        };

        let showSenderName = (index) => {
            let item = dropDownSenders.value[index];
            nameSender.value = item.name;
            changeStatus.data.product_id = item.id;
        };

        let senderHover = (e) => {
            let items = document.querySelectorAll('.sender-search .Sender');
            items.forEach(e => e.classList.remove('active'));
            e.target.classList.add('active');
        };

        function changeLeadClient(id,index) {
            Swal.fire({
                title: 'هل انت متأكد من تحويل العميل إلى عميل حقيقى',
                text: `لم تتمكن من التراجع عن هذا`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    adminApi.post(`/v1/dashboard/changeLeadClient/${id}`)
                    .then((res) => {
                        Swal.fire({
                            icon: 'success',
                            title: `${parseInt(status) ? 'تم ايقاف التفعيل بنجاح' : 'تم التفعيل بنجاح'}`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        clients.value[index]['status'] =  parseInt(status) ? 0:1
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
        const rules = computed(() => {
            return {
                lead_follow_up_id:{
                    required
                },
                lead_Sources_id:{
                    required
                },
                note:{}
            }
        });

        const v$ = useVuelidate(rules,changeStatus.data);

        // document.addEventListener('keyup',(e) => {
        //
        //     if(e.keyCode == 38){ //top arrow
        //         if(dropDownSenders.value.length > 0){
        //             let items = document.querySelectorAll('.sender-search .Sender');
        //             let isTrue = false;
        //             let index = null;
        //             items.forEach((e,i) => {
        //                 if(e.classList.contains('active')) {
        //                     isTrue = true;
        //                     index = i;
        //                 }
        //             });
        //             if(isTrue && index != 0){
        //                 items[index].classList.remove('active');
        //                 items[index - 1].classList.add('active');
        //             }else if(isTrue && index == 0){
        //                 items[index].classList.remove('active');
        //                 items[items.length - 1].classList.add('active');
        //             }
        //             if(!isTrue) items[0].classList.add('active');
        //         }else {
        //             dropDownSenders.value = [];
        //         }
        //     };
        //
        //     if(e.keyCode == 40){ //down arrow
        //         if(dropDownSenders.value.length > 0){
        //             let items = document.querySelectorAll('.sender-search .Sender');
        //             let isTrue = false;
        //             let index = null;
        //             items.forEach((e,i) => {
        //                 if(e.classList.contains('active')) {
        //                     isTrue = true;
        //                     index = i;
        //                 }
        //             });
        //             if(isTrue && index != (items.length - 1)){
        //                 items[index].classList.remove('active');
        //                 items[index + 1].classList.add('active');
        //             }else if(isTrue && index == (items.length - 1)){
        //                 items[index].classList.remove('active');
        //                 items[0].classList.add('active');
        //             }
        //             if(!isTrue) items[items.length - 1].classList.add('active');
        //         }else {
        //             dropDownSenders.value = [];
        //         }
        //     };
        //
        //     if(e.keyCode == 13){ //enter
        //
        //         if(dropDownSenders.value.length > 0){
        //             let items = document.querySelectorAll('.sender-search .Sender');
        //             items.forEach((e,i) => {
        //                 if(e.classList.contains('active')) showSenderName(i);
        //             });
        //         }else {
        //             dropDownSenders.value = [];
        //         }
        //
        //         e.target.blur();
        //
        //     };
        //
        // });
        //
        // document.addEventListener('click',(e) => {
        //     if(dropDownSenders.value.length > 0){
        //         if(!e.target.classList.contains('Sender') && !e.target.classList.contains('input-Sender') && e.pointerType){
        //             dropDownSenders.value = [];
        //         }
        //     }
        //
        //     addJob.products.forEach((item,ind) => {
        //         if(item.products.length > 0){
        //             if(
        //                 !e.target.classList.contains(`Sender-${ind}`) &&
        //                 !e.target.classList.contains(`input-Sender`) &&
        //                 e.pointerType
        //             ) {
        //                 item.products = [];
        //             }
        //         }
        //     });
        // });

        return {
            v$,
            getClient,
            deleteMeasure,
            getLeadClients,
            loading,
            ...toRefs(changeStatus),
            permission,
            search,
            leadClients,
            leadFollows,
            leadSources,
            dropDownSenders,
            t,
            leadClientsPaginate,
            searchProduct,
            searchSender,
            senderHover,
            showSenderName,
            changeLeadClient
        };

    },
    data() {
        return {
            locale: localStorage.getItem("langAdmin"),
            errors:{}
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

.amount h5{
    font-weight: 700;
    text-align: center;
}
.head-table h3{
    color: #ffc107;
    text-align: center;
}

.custom-modal .close span {
    top: 0 !important;
}
.modal-dialog {
    max-width: 1000px !important;
}

.edit-envoice .modal-dialog{
    max-width: 500px !important;
}

/*======== print ===========*/

.image-div img {
    width: 35%;
}

img {
    max-width: inherit;
    width: inherit;
}


.dropdown-search{
    position: absolute;
    width: 97%;
    background-color: #fff;
    border: 1px solid #d9d3d3;
    top: 83px;
    z-index: 10;
    padding: 0;
}

.dropdown-search li{
    padding: 5px;
}

.dropdown-search li:not(:last-child){
    border-bottom: 1px solid #d9d3d3;
}

.dropdown-search li:hover{
    background-color: #f1f1f1;
    cursor: pointer;
}

.dropdown-search li.active{
    background-color: #f1f1f1;
    cursor: pointer;
}

</style>
