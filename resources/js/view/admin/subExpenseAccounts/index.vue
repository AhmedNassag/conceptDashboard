<template>
    <div :class="['page-wrapper',this.$i18n.locale == 'ar'? 'page-wrapper-ar':'']">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="row">
                            <h3 class="page-title col-md-2">{{ $t('global.Expenses') }}</h3>
                            <p class=" col-md-2" v-if="main.debit == 1">{{ $t('global.AccountType') }} :: {{ $t('global.Debit') }}</p>
                            <p class=" col-md-2" v-if="main.debit == 0">{{ $t('global.AccountType') }} :: {{ $t('global.Creditor') }}</p>
                            <p class=" col-md-2">{{ $t('global.NumberOfElements') }} :: {{incomes.length}}</p>
                            <p class="col-md-2">{{ $t('global.Debit') }} :: {{main.debit_amount}}</p>
                            <p class="col-md-2">{{ $t('global.Creditor') }} :: {{main.credit_amount}}</p>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'dashboard', params: {lang: locale || 'ar'}}">
                                    {{ $t('dashboard.Dashboard') }}
                                </router-link>
                            </li>

                            <li class="breadcrumb-item">
                                <router-link :to="{name: 'indexExpenseAccounts', params: {lang: locale || 'ar'}}">
                                    {{ $t('global.Expenses') }}
                                </router-link>
                            </li>

                            <li  :class="['breadcrumb-item']" v-for=" (it,key) in mainData">
                                <router-link @click="clickFunc"
                                    v-if="id != it.id" :to="{name: 'indexSubExpenseAccounts', params: {lang: locale || 'ar',id:it.id,mainId:3}}">
                                    {{ it.name }}
                                </router-link>
                            </li>
                            <span v-for=" (it,key) in mainData" >
                            <li v-if="id == it.id" :class="['breadcrumb-item',(mainData.length-1) == key? 'active':'']" >

                                 {{ it.name }}
                            </li>
                                </span>
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
                                    <div class="col-5 row justify-content-end">
                                        <router-link v-if="permission.includes('AccountsTree create')"
                                            :to="{name: 'createSubExpenseAccounts', params: {lang: locale || 'ar',id:id,mainId:mainId}}"
                                            class="btn btn-custom btn-warning">
                                            {{ $t('global.Add') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ $t('global.Name') }}</th>
                                        <th>{{ $t('global.AccountType') }}</th>
                                        <th>{{ $t('global.NumberOfElements') }}</th>
                                        <th>{{ $t('global.Debit') }}</th>
                                        <th>{{ $t('global.Creditor') }}</th>
                                        <th>{{ $t('global.RelatedTo') }}</th>
                                        <th>{{ $t('global.AddedDate') }}</th>
                                        <th>{{ $t('global.Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="incomes.length">
                                    <tr v-for="(item,index) in incomes" :key="item.id">
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.name }}</td>
                                        <td v-if="item.debit == 1">{{ $t('global.Debit') }}</td>
                                        <td v-if="item.debit == 0">{{ $t('global.Creditor') }}</td>
                                        <td>{{ item.count_elements }}</td>
                                        <td>{{ item.debit_amount }}</td>
                                        <td>{{ item.credit_amount }}</td>
                                        <td>
                                            {{ item.parent.name }}
                                        </td>

                                        <td>
                                            {{ dateFormat(item.created_at) }}
                                        </td>

                                        <td>

                                            <router-link v-if="permission.includes('AccountsTree edit')"
                                                :to="{name: 'editSubExpenseAccounts', params: {lang: locale || 'ar',id:item.id,mainId:3}}"
                                                class="btn btn-sm btn-success me-2">
                                                <i class="far fa-edit"></i>
                                            </router-link>

                                            <router-link @click="clickFunc"
                                                :to="{name: 'indexSubExpenseAccounts', params: {lang: locale || 'ar',id:item.id,mainId:3}}"
                                                class="btn btn-sm btn-blue me-2">
                                                <i class="far fa-eye"></i>
                                            </router-link>

<!--                                            <a href="#" @click="deleteIncome(item.id,item.name,index)"-->
<!--                                               data-bs-target="#staticBackdrop" class="btn btn-sm btn-danger me-2">-->
<!--                                                <i class="far fa-trash-alt"></i>-->
<!--                                            </a>-->
                                        </td>

                                    </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr >
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
            <Pagination :limit="2" :data="incomesPaginate" @pagination-change-page="getIncome">
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
import {onMounted, inject, watch, ref, toRefs, computed} from "vue";
import {useStore} from "vuex";
import adminApi from "../../../api/adminAxios";
import {useI18n} from "vue-i18n";

export default {
    name: "index",
    props:['mainId','id'],
    setup(props) {
        const emitter = inject('emitter');
        const {t} = useI18n({});
        const {id,mainId} = toRefs(props);
        let store = useStore();
        let permission = computed(() => store.getters['authAdmin/permission']);
        let incomes = ref([]);
        let mainData = ref([]);
        let incomesPaginate = ref({});
        let loading = ref(false);
        const search = ref('');
        let main = ref({});


        let getIncome = (page = 1) => {
            loading.value = true;

            adminApi.get(`/v1/dashboard/subAccount/${mainId.value}/${id.value}?page=${page}&search=${search.value}`)
                .then((res) => {
                    main.value ={};
                    let l = res.data.data;
                    incomesPaginate.value = l.subAccounts;
                    incomes.value = l.subAccounts.data;
                    mainData.value = l.data;

                    if (l.subAccounts.data[0].parent)
                    {
                        main.value = l.subAccounts.data[0].parent;
                    }else {
                        main.value ={};
                    }
                })
                .catch((err) => {
                    console.log(err.response.data);
                })
                .finally(() => {
                    loading.value = false;
                });
        }

        const clickFunc = async ()=>{
            loading.value = true;
            setTimeout(() => {
                getIncome()
            },1000)
        }
        onMounted(() => {
            getIncome();
        });

        emitter.on('get_lang', () => {
            getIncome(search.value);
        });

        watch(search, (search, prevSearch) => {
            if (search.length >= 0) {
                getIncome();
            }
        });


        function deleteIncome(id, incomeName, index) {
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

                    adminApi.delete(`/v1/dashboard/income/${id}`)
                        .then((res) => {
                            incomes.value.splice(index, index + 1);

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
            let now = new Date(item);
            let st = `
                 ${now.getUTCHours()}:${now.getUTCMinutes()}:${now.getUTCSeconds()}
                ${now.getUTCFullYear().toString()}
                 /${(now.getUTCMonth() + 1).toString()}
                 /${now.getUTCDate()}
            `;
            return st;
        }


        return {main,id,mainId,mainData,incomes, loading, getIncome,dateFormat, search,permission, deleteIncome,clickFunc, incomesPaginate};

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

</style>
