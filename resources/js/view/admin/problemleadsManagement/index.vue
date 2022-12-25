<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{ $t('global.problemLeadsManagement') }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>
                            <li class="breadcrumb-item active">{{ $t('global.problemLeadsManagement') }}</li>
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
                                    <div class="col-2 row justify-content-end">
                                        <router-link v-if="permission.includes('problemLeadsManagement create')" :to="{name: 'createProblemLeadsManagement', params: {lang: locale || 'ar'}}" class="btn btn-custom btn-warning">
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">{{ $t('global.Name') }}</th>
                                        <th class="text-center">{{ $t('global.Phone') }}</th>
                                        <th class="text-center">{{ $t('global.Address') }}</th>
                                        <th class="text-center">{{ $t('global.Description') }}</th>
                                        <th class="text-center">{{ $t('global.RelatedTo') }}</th>
                                        <th class="text-center">{{ $t('global.Employee') }}</th>
                                        <th class="text-center">{{ $t('global.AddedDate') }}</th>
                                        <th class="text-center">{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="leads.length">
                                    <tr v-for="(item,index) in leads" :key="item.id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">{{ item.name }}</td>
                                        <td class="text-center">{{ item.phone }}</td>
                                        <td class="text-center">{{ item.address ? item.address : "---"}}</td>
                                        <td class="text-center">{{ item.description ? item.description : "---"}}</td>
                                        <td class="text-center">{{ item.seller_category.name }}</td>
                                        <td class="text-center">{{ item.employee ? item.employee.user.name : "---" }}</td>
                                        <td class="text-center">
                                            {{ dateFormat(item.created_at) }}
                                        </td>
                                        <td class="text-center">

                                            <router-link :to="{name: 'editProblemLeadsManagement', params: {lang: locale || 'ar',id:item.id}}" v-if="permission.includes('problemLeadsManagement edit')" class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <a href="#" @click="deleteLead(item.id,item.name,index)"
                                               v-if="permission.includes('LeadsManagement delete')"
                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">
                                               <i class="far fa-trash-alt"></i>
                                            </a>

<!--                                            <router-link v-if="permission.includes('problemLeadsManagement changeEmployee')" :to="{name: 'ProblemChangeEmployee', params: {lang: locale || 'ar',id:item.id}}" class="btn btn-sm btn-warning me-2">-->
<!--                                                <i class="fa fa-user-plus"></i>-->
<!--                                            </router-link>-->

                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                               :data-bs-target="'#show-lead'+item.id"
                                               v-if="item.comments.length > 0">
                                                <i class="fas fa-book-open"></i>
                                            </a>

                                        </td>
                                        <!-- Edit Modal -->
                                        <div class="modal fade custom-modal" :id="'show-lead'+item.id">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" id="print">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            {{ $t('global.SalesEmployeeComments') }}
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
                                                                    <div role="tabpanel" :id="'tab-4-'+item.id" class="tab-pane fade active show">
                                                                        <loader v-if="loading"/>
                                                                        <div class="row justify-content-between">
                                                                            <div class="col-5">
                                                                                <button @click="printData(item.id)" class="btn btn-secondary print-button head-button">
                                                                                    {{$t('global.Print')}}
                                                                                    <i class="fa fa-print"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive" :id="'printData-'+item.id">

                                                                            <table class="table table-center table-hover mb-0 datatable">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>#</th>
                                                                                    <th>{{ $t('global.Employee') }}</th>
                                                                                    <th>{{ $t('global.Comment') }}</th>
                                                                                    <th>{{ $t('global.Date') }}</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr v-for="(it,index) in item.comments" v-if="item.comments" :key="it.id">
                                                                                        <td>{{ index +1}}</td>
                                                                                        <td>{{ it.employee.user.name }}</td>
                                                                                        <td>{{ it.comment }}</td>
                                                                                        <td>{{ dateFormat(it.created_at) }}</td>
                                                                                    </tr>
                                                                                    <tr v-else>
                                                                                        <th class="text-center" colspan="4">{{ $t('global.NoDataFound') }}</th>
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
            <Pagination :data="LeadsPaginate" @pagination-change-page="getLead">
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
import {onMounted, inject, watch, ref,computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    setup() {
        const emitter = inject('emitter');
        const {t} = useI18n({});
        let leads = ref([]);
        let LeadsPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);

        let getLead = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/problemleads?page=${page}&search=${search.value}`)
                .then((res) => {
                    let l = res.data.data;
                    LeadsPaginate.value = l.leads;
                    leads.value = l.leads.data;
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        onMounted(() => {
            getLead();
        });

        emitter.on('get_lang', () => {
            getLead(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getLead();
            }
        });


        function deleteLead(id, incomeName, index) {
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

                    adminApi.delete(`/v1/dashboard/problemleads/${id}`)
                        .then((res) => {
                            leads.value.splice(index, 1);

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
        let close=(id)=>{
            document.getElementById('close-'+id).click();
        }

        let printData = (id) => {
            var printContents = document.getElementById('printData-'+id).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        return {leads,permission, loading, getLead,dateFormat, search, deleteLead, LeadsPaginate,close,printData};

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
    background-color: #0E67D0;
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
    color: #0e67d0;
    text-align: center;
}
.total-head{
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    background-color: #0e67d0 !important;
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
    width: 200px;
    margin-bottom: 5px;
}

</style>
