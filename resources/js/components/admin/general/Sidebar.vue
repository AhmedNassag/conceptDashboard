<template>
<!-- Sidebar -->
    <div :class="['sidebar sidebar-ar']" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li :class="[$route.name == 'dashboard'? 'active': '']">
                        <router-link :to="{name:'dashboard'}" >
                            <i class="fas fa-users"></i>
                            <span>الرئيسية</span>
                        </router-link>
                    </li>

                    <!--start supplier-->
                    <li
                        :class="[{'active': ['indexSupplier','createSupplier','editSupplier'].includes($route.name)}]"
                        v-if="permission.includes('supplier read')"
                    >
                        <router-link :to="{name:'indexSupplier'}" >
                            <i class="fas fa-parachute-box"></i>
                            <span>{{ $t('sidebar.supplier') }}</span>
                        </router-link>
                    </li>
                    <!--start supplier-->

                    <!--start all clients-->
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i><span>إدارة العملاء</span><span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <!--start client-->
                            <li
                                :class="[{'active': ['indexClient','createClient','editClient'].includes($route.name)}]"
                                v-if="permission.includes('CreditCapacity read')"
                            >
                                <router-link :to="{name:'indexClient'}" :class="['sidebar-menu-rtl']">
                                    العملاء
                                </router-link>
                            </li>
                            <!--end client-->

                            <!--start userCompany-->
                            <li
                                :class="[{'active': ['indexUserCompany','createUserCompany','editUserCompany','showUserCompany'].includes($route.name)}]"
                                v-if="permission.includes('CreditCapacity read')"
                            >
                                <router-link :to="{name:'indexUserCompany'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.userCompany') }}
                                </router-link>
                            </li>
                            <!--end userCompany-->
                        </ul>
                    </li>
                    <!--end all clients-->


                    <!--start merchant-->
<!--                    <li-->
<!--                        :class="[{'active': ['indexMerchant','createMerchant','editMerchant','showMerchant'].includes($route.name)}]"-->
<!--                        v-if="permission.includes('CreditCapacity read')"-->
<!--                    >-->
<!--                        <router-link :to="{name:'indexMerchant'}" >-->
<!--                            <i class="fas fa-users"></i>-->
<!--                            <span>{{ $t('sidebar.merchant') }}</span>-->
<!--                        </router-link>-->
<!--                    </li>-->
                    <!--end merchant-->

                    <!--start leadFollowUps-->
<!--                    <li-->
<!--                        :class="[{'active': ['indexLeadFollowUps','editLeadFollowUps','createLeadFollowUps'].includes($route.name)}]"-->
<!--                        v-if="permission.includes('CreditCapacity read')"-->
<!--                    >-->
<!--                        <router-link :to="{name:'indexLeadFollowUps'}" >-->
<!--                            <i class="fas fa-users"></i>-->
<!--                            <span>{{ $t('sidebar.LeadFollowUps') }}</span>-->
<!--                        </router-link>-->
<!--                    </li>-->
                    <!--end leadFollowUps-->

                    <!--start LeadSourse-->
<!--                    <li-->
<!--                        :class="[{'active': ['indexLeadSourse','createLeadSourse','editLeadSourse'].includes($route.name)}]"-->
<!--                        v-if="permission.includes('CreditCapacity read')"-->
<!--                    >-->
<!--                        <router-link :to="{name:'indexLeadSourse'}" >-->
<!--                            <i class="fas fa-users"></i>-->
<!--                            <span>{{ $t('sidebar.leadSourse') }}</span>-->
<!--                        </router-link>-->
<!--                    </li>-->
                    <!--end leadFollowUps-->

                    <!--start Lead-->
<!--                    <li-->
<!--                        :class="[{'active': ['indexLead','createLead','editLead'].includes($route.name)}]"-->
<!--                        v-if="permission.includes('CreditCapacity read')"-->
<!--                    >-->
<!--                        <router-link :to="{name:'indexLead'}" >-->
<!--                            <i class="fas fa-users"></i>-->
<!--                            <span>{{ $t('sidebar.lead') }}</span>-->
<!--                        </router-link>-->
<!--                    </li>-->
                    <!--end Lead-->

                    <!--start LeadClient-->
<!--                    <li-->
<!--                        :class="[{'active': ['indexLeadClient','createLeadClient','editLeadClient'].includes($route.name)}]"-->
<!--                        v-if="permission.includes('CreditCapacity read')"-->
<!--                    >-->
<!--                        <router-link :to="{name:'indexLeadClient'}" >-->
<!--                            <i class="fas fa-users"></i>-->
<!--                            <span>{{ $t('sidebar.leadClient') }}</span>-->
<!--                        </router-link>-->
<!--                    </li>-->
                    <!--end LeadClient-->


                    <!--start product-->
                    <li class="submenu" v-if="permission.includes('productManagement')">
                        <a href="#" ><i class="fab fa-product-hunt"></i> <span> {{ $t('sidebar.ProductManagement') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['indexCategory','createCategory','editCategory'].includes($route.name)}]"
                                v-if="permission.includes('category read')"
                            >
                                <router-link :to="{name:'indexCategory'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.category') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexSubCategory','createSubCategory','editSubCategory'].includes($route.name)}]"
                                v-if="permission.includes('category read')"
                            >
                                <router-link :to="{name:'indexSubCategory'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.subCategory') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexCompany','createCompany','editCompany'].includes($route.name)}]"
                                v-if="permission.includes('company read')"
                            >
                                <router-link :to="{name:'indexCompany'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.company') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexMeasure','createMeasure','editMeasure'].includes($route.name)}]"
                                v-if="permission.includes('measureUnit read')"
                            >
                                <router-link :to="{name:'indexMeasure'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Measurement') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexSellingMethod','createSellingMethod','editSellingMethod'].includes($route.name)}]"
                                v-if="permission.includes('sellingMethod read')"
                            >
                                <router-link :to="{name:'indexSellingMethod'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Selling_methods') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexFilterWax','createFilterWax','editFilterWax'].includes($route.name)}]"
                                v-if="permission.includes('sellingMethod read')"
                            >
                                <router-link :to="{name:'indexFilterWax'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.wax') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexSparePart','createSparePart','editSparePart'].includes($route.name)}]"
                                v-if="permission.includes('sellingMethod read')"
                            >
                                <router-link :to="{name:'indexSparePart'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.SparePart') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexSpareAccessor','createSpareAccessor','editSpareAccessor'].includes($route.name)}]"
                                v-if="permission.includes('sellingMethod read')"
                            >
                                <router-link :to="{name:'indexSpareAccessor'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.SpareAccessor') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexProduct','createProduct','editProduct'].includes($route.name)}]"
                                v-if="permission.includes('product read')"
                            >
                                <router-link :to="{name:'indexProduct'}" :class="['sidebar-menu-rtl']">
                                    أجهزة الفلاتر
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end product-->


                    <!--start buy-->
                    <li class="submenu" v-if="permission.includes('buy')">
                        <a href="#" ><i class="fas fa-box-open"></i> <span> {{ $t('global.purchaseManagement') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['createPurchaseInvoice','editPurchaseInvoice','indexPurchaseInvoice'].includes($route.name)}]"
                                v-if="permission.includes('PurchaseInvoice read')"
                            >
                                <router-link :to="{name:'indexPurchaseInvoice'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.PurchaseInvoice') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexPurchaseReturn','createPurchaseReturn'].includes($route.name)}]"
                                v-if="permission.includes('PurchaseReturn read')"
                            >
                                <router-link :to="{name:'indexPurchaseReturn'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.PurchaseReturn') }}
                                </router-link>
                            </li>

<!--                            <li-->
<!--                                :class="[{'active': ['indexEarnedDiscount','createEarnedDiscount','editEarnedDiscount'].includes($route.name)}]"-->
<!--                                v-if="permission.includes('EarnedDiscount read')"-->
<!--                            >-->
<!--                                <router-link :to="{name:'indexEarnedDiscount'}" :class="['sidebar-menu-rtl']">-->
<!--                                    {{ $t('global.EarnedDiscount') }}-->
<!--                                </router-link>-->
<!--                            </li>-->

                        </ul>
                    </li>
                    <!--end buy-->


                    <!--start store-->
                    <li class="submenu" v-if="permission.includes('StoreManagement')">
                        <a href="#" ><i class="fas fa-cubes"></i> <span> {{ $t('sidebar.StoreManagement') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['indexStore','createStore','editStore'].includes($route.name)}]"
                                v-if="permission.includes('store read')"
                            >
                                <router-link :to="{name:'indexStore'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.Store') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexProductsPricing'].includes($route.name)}]"
                                v-if="permission.includes('ProductsPricing read')"
                            >
                                <router-link :to="{name:'indexProductsPricing'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.ProductsPricing') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexExaminationRecord','editExaminationRecord','createExaminationRecord'].includes($route.name)}]"
                                v-if="permission.includes('examinationRecords read')"
                            >
                                <router-link :to="{name:'indexExaminationRecord'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.examinationRecords') }}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end store-->

                    <!--start order-->
                    <li class="submenu" v-if="permission.includes('order')">
                        <a href="#" ><i class="fas fa-shopping-cart"></i> <span> {{$t('global.orders')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <li
                                :class="[{'active': ['indexOrderDirect','createOrderDirect','editOrderDirect'].includes($route.name)}]"
                                v-if="permission.includes('order read')"
                            >
                                <router-link :to="{name:'indexOrderDirect'}" :class="['sidebar-menu-rtl']">
                                    {{$t('sidebar.OrderDirect')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexOrderOnline','editOrderOnline','showOrderOnline'].includes($route.name)}]"
                                v-if="permission.includes('orderOnline read')"
                            >
                                <router-link :to="{name:'indexOrderOnline'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.orderOnline')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexOrderReturned','showOrderReturned'].includes($route.name)}]"
                                v-if="permission.includes('orderReturned read')"
                            >
                                <router-link :to="{name:'indexOrderReturned'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.orderReturned')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexOrderDelivered','showOrderDelivered'].includes($route.name)}]"

                                v-if="permission.includes('orderDelivered read')"
                            >
                                <router-link :to="{name:'indexOrderDelivered'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.orderDelivered')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end order-->

                    <!--start employee-->
                    <li class="submenu" v-if="permission.includes('role-employee')">
                        <a href="#" ><i class="fas fa-user-tie"></i> <span> {{ $t('global.EmployeesManagement') }}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['indexDepartment','createDepartment','editDepartment'].includes($route.name)}]"
                                v-if="permission.includes('department read')"
                            >
                                <router-link :to="{name:'indexDepartment'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Department') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexJob','createJob','editJob'].includes($route.name)}]"
                                v-if="permission.includes('job read')"
                            >
                                <router-link :to="{name:'indexJob'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Jobs') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexRole','createRole','editRole'].includes($route.name)}]"
                                v-if="permission.includes('role read')"
                            >
                                <router-link :to="{name:'indexRole'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Roles') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexEmployee','createEmployee','editEmployee'].includes($route.name)}]"
                                v-if="permission.includes('employee read')"
                            >
                                <router-link :to="{name:'indexEmployee'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('sidebar.Employees') }}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexRepresentative','createRepresentative','editRepresentative','changePasswordRepresentative'].includes($route.name)}]"
                                v-if="permission.includes('representative read')"
                            >
                                <router-link :to="{name:'indexRepresentative'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.representative')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end employee-->


                    <!-- Start CRM Links -->
                    <li class="submenu" v-if="permission.includes('CRM')">
                        <a href="#"><i class="fa fa-home"></i><span> {{ $t("global.ClientRelationManagement") }} </span><span :class="['menu-arrow',this.$i18n.locale == 'ar' ? 'menu-arrow-ar' : '']"></span></a>
                        <ul>

                            <li v-if="permission.includes('targetPlan read')" :class="[{'active': ['indexTargetPlan','createTargetPlan','editTargetPlan'].includes($route.name)}]">
                                <router-link :to="{name: 'indexTargetPlan'}" :class="['sidebar-menu-rtl']">
                                    {{ $t("global.CommissionPlanManagement") }}
                                </router-link>
                            </li>

                            <li v-if="permission.includes('SellerCategory read')" :class="[{'active': ['indexSellerCategory','indexSellerCategory','indexSellerCategory'].includes($route.name)}]">
                                <router-link :to="{name: 'indexSellerCategory'}" :class="['sidebar-menu-rtl']">
                                    {{ $t("global.EmployeesTargets") }}
                                </router-link>
                            </li>

                            <li v-if="permission.includes('TargetAchieved read')" :class="[{'active': ['indexTargetAchievedHome','indexTargetAchievedHome','indexTargetAchievedHome'].includes($route.name)}]">
                                <router-link :to="{name: 'indexTargetAchievedHome'}" :class="['sidebar-menu-rtl']">
                                    {{ $t("global.TargetAchieved") }}
                                </router-link>
                            </li>

                            <li v-if="permission.includes('LeadsManagement read')" :class="[{'active': ['indexLeadsManagement','indexLeadsManagement','indexLeadsManagement'].includes($route.name)}]">
                                <router-link :to="{name: 'indexLeadsManagement'}" :class="['sidebar-menu-rtl']">
                                    {{ $t("global.LeadsManagement") }}
                                </router-link>
                            </li>

                            <li v-if="permission.includes('Leads read')" :class="[{'active': ['indexLeadSalesHome','indexLeadSalesHome','indexLeadSalesHome'].includes($route.name)}]">
                                <router-link :to="{name: 'indexLeadSalesHome'}" :class="['sidebar-menu-rtl']">
                                    {{ $t("global.Leads") }}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!-- End CRM Links -->


                    <!--start customer service-->
                    <li class="submenu">
                        <a href="#" ><i class="fas fa-boxes"></i> <span>إدارة خدمة العملاء</span><span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <!-- Start follow Links -->
                            <li class="submenu" v-if="permission.includes('CRM')">
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i  :class="['fa fa-home',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                    خدمة ما بعد البيع
                                    <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span>
                                </a>
                                <ul>
                                    <li v-if="permission.includes('followSellerCategory read')" :class="[$route.name == 'indexfollowSellerCategory' ? 'active' : '']">
                                        <router-link :to="{name: 'indexfollowSellerCategory',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.EmployeesTargets") }}
                                        </router-link>
                                    </li>
                                    <li v-if="permission.includes('followLeadsManagement read')" :class="[$route.name == 'indexfollowLeadsManagement' ? 'active' : '']">
                                        <router-link :to="{name: 'indexfollowLeadsManagement',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.followLeadsManagement") }}
                                        </router-link>
                                    </li>
                                    <li v-if="permission.includes('followLeads read')" :class="[$route.name == 'indexfollowLeadSalesHome' ? 'active' : '']">
                                        <router-link :to="{name: 'indexfollowLeadSalesHome',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.followLeads") }}
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                            <!-- End follow Links -->

                            <!-- Start problem Links -->
                            <li class="submenu" v-if="permission.includes('CRM')">
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i  :class="['fa fa-home',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                    الأعطال
                                    <span :class="['menu-arrow menu-arrow-ar']"></span>
                                </a>
                                <ul>
                                    <li v-if="permission.includes('problemSellerCategory read')" :class="[$route.name == 'indexProblemSellerCategory' ? 'active' : '']">
                                        <router-link :to="{name: 'indexProblemSellerCategory',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.EmployeesTargets") }}
                                        </router-link>
                                    </li>
                                    <li v-if="permission.includes('problemLeadsManagement read')" :class="[$route.name == 'indexProblemLeadsManagement' ? 'active' : '']">
                                        <router-link :to="{name: 'indexProblemLeadsManagement',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.ProblemLeadsManagement") }}
                                        </router-link>
                                    </li>
                                    <li v-if="permission.includes('problemLeads read')" :class="[$route.name == 'indexProblemLeadSalesHome' ? 'active' : '']">
                                        <router-link :to="{name: 'indexProblemLeadSalesHome',params: { lang: this.$i18n.locale }}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl' : '']">
                                            {{ $t("global.ProblemLeads") }}
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                            <!-- End problem Links -->

                            <!--start PeriodicMaintenance-->
                            <li>
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i :class="['fa fa-home',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                    الصيانة الدورية
                                    <span :class="['menu-arrow menu-arrow-ar']"></span>
                                </a>
                                <ul>
                                    <li
                                        :class="[$route.name == ['indexPeriodicMaintenance','createPeriodicMaintenance','editPeriodicMaintenance'] ? 'active' : '']"
                                        v-if="permission.includes('periodicMaintenance read')"
                                    >
                                        <router-link :to="{name:'indexPeriodicMaintenance'}" :class="['sidebar-menu-rtl']">
                                            {{ $t('sidebar.Maintenance') }}
                                        </router-link>
                                    </li>
                                    <li
                                        :class="[$route.name == ['nearPeriodicMaintenance','delayPeriodicMaintenance','confirmPeriodicMaintenance'] ? 'active' : '']"
                                        v-if="permission.includes('periodicMaintenance read')"
                                    >
                                        <router-link :to="{name:'nearPeriodicMaintenance'}" :class="['sidebar-menu-rtl']">
                                            {{ $t('sidebar.nearMaintenance') }}
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                            <!--end PeriodicMaintenance-->
                        </ul>
                    </li>
                    <!--end customer service-->


                    <li class="submenu" v-if="permission.includes('platform Accounts')">
                        <a href="#" ><i class="fas fa-coins"></i> <span> {{$t('sidebar.Accounts')}}</span>  <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span></a>
                        <ul>
                            <li :class="[$route.name == 'indexTreasury'? 'active': '']" v-if="permission.includes('treasury read')">
                                <router-link :to="{name:'indexTreasury',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('sidebar.TreasuryManagement')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexIncome'? 'active': '']" v-if="permission.includes('income read')">
                                <router-link :to="{name:'indexIncome',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.IncomeItems')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexExpense'? 'active': '']" v-if="permission.includes('expense read')">
                                <router-link :to="{name:'indexExpense',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.ExpenseItems')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexIncomeAndExpense'? 'active': '']" v-if="permission.includes('income&expense read')">
                                <router-link :to="{name:'indexIncomeAndExpense',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('sidebar.IncomeAndExpense')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexSupplierExpenses'? 'active': '']" v-if="permission.includes('supplierExpenses read')">
                                <router-link :to="{name:'indexSupplierExpenses',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.supplierExpenses')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexClientExpenses'? 'active': '']" v-if="permission.includes('clientExpenses read')">
                                <router-link :to="{name:'indexClientExpenses',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.clientExpenses')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexPurchaseExpenses'? 'active': '']" v-if="permission.includes('purchaseExpenses read')">
                                <router-link :to="{name:'indexPurchaseExpenses',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.purchaseExpenses')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexSupplierIncomes'? 'active': '']" v-if="permission.includes('SupplierIncomes read')">
                                <router-link :to="{name:'indexSupplierIncomes',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.SupplierIncomes')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexClientIncomes'? 'active': '']" v-if="permission.includes('clientIncomes read')">
                                <router-link :to="{name:'indexClientIncomes',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.clientIncomes')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexOrderIncomes'? 'active': '']" v-if="permission.includes('orderIncomes read')">
                                <router-link :to="{name:'indexOrderIncomes',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.orderIncome')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexPurchaseReturnIncomes'? 'active': '']" v-if="permission.includes('purchaseReturnIncomes read')">
                                <router-link :to="{name:'indexPurchaseReturnIncomes',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.IncomesPurchaseReturn')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexCapitalOwnerAccount'? 'active': '']" v-if="permission.includes('CapitalOwnerAccount read')">
                                <router-link :to="{name:'indexCapitalOwnerAccount',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.capitalOwnerAccount')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'treasuriesIncome'? 'active': '']" v-if="permission.includes('treasuriesIncome read')">
                                <router-link :to="{name:'treasuriesIncome',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('sidebar.treasuriesIncome')}}
                                </router-link>
                            </li>
                            <li :class="[$route.name == 'treasuriesExpense'? 'active': '']" v-if="permission.includes('treasuriesExpense read')">
                                <router-link :to="{name:'treasuriesExpense',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.treasuriesExpense')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexTransferringTreasury'? 'active': '']" v-if="permission.includes('transferringTreasury read')">
                                <router-link :to="{name:'indexTransferringTreasury',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.TransferringTreasury')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexSupplierAccountStatement'? 'active': '']" v-if="permission.includes('supplierAccountStatement read')">
                                <router-link :to="{name:'indexSupplierAccountStatement',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.supplierAccountStatement')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexClientAccountStatement'? 'active': '']" v-if="permission.includes('clientAccountStatement read')">
                                <router-link :to="{name:'indexClientAccountStatement',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.clientAccountStatement')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>

                    <!--start financial Accounts-->
                    <li class="submenu" v-if="permission.includes('financial Accounts')">
                        <a href="#" ><i class="fas fa-boxes"></i> <span> {{$t('sidebar.FinancialAccounts')}}</span>  <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span></a>
                        <ul>

                            <li v-if="permission.includes('AccountsTree read')">
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i  :class="['fa fa-tree',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                    {{$t('global.AccountsTree')}}
                                    <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span>
                                </a>
                                <ul>

                                    <li :class="[$route.name == 'indexAssets'? 'active': '']" v-if="permission.includes('AccountsTree read')">
                                        <router-link :to="{name:'indexAssets',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                            {{$t('sidebar.Assets')}}
                                        </router-link>
                                    </li>

                                    <li :class="[$route.name == 'indexOpponents'? 'active': '']" v-if="permission.includes('AccountsTree read')">
                                        <router-link :to="{name:'indexOpponents',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                            {{$t('global.Opponents')}}
                                        </router-link>
                                    </li>

                                    <li :class="[$route.name == 'indexExpenseAccounts'? 'active': '']" v-if="permission.includes('AccountsTree read')">
                                        <router-link :to="{name:'indexExpenseAccounts',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                            {{$t('global.Expenses')}}
                                        </router-link>
                                    </li>

                                    <li :class="[$route.name == 'indexIncomeAccounts'? 'active': '']" v-if="permission.includes('AccountsTree read')">
                                        <router-link :to="{name:'indexIncomeAccounts',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                            {{$t('global.Incomes')}}
                                        </router-link>
                                    </li>

                                </ul>
                            </li>

                            <li :class="[$route.name == 'indexDailyRestriction'? 'active': '']" v-if="permission.includes('DailyRestriction read')">
                                <router-link :to="{name:'indexDailyRestriction',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.DailyRestriction')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexTrialBalance'? 'active': '']" v-if="permission.includes('TrialBalance read')">
                                <router-link :to="{name:'indexTrialBalance',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.TrialBalance')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexFinancialCenter'? 'active': '']" v-if="permission.includes('FinancialCenter read')">
                                <router-link :to="{name:'indexFinancialCenter',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.FinancialCenter')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexIncomeList'? 'active': '']" v-if="permission.includes('IncomeList read')">
                                <router-link :to="{name:'indexIncomeList',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.IncomeList')}}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'indexAccountStatement'? 'active': '']" v-if="permission.includes('AccountStatement read')">
                                <router-link :to="{name:'indexAccountStatement',params: {lang:this.$i18n.locale}}" :class="[this.$i18n.locale == 'ar' ? 'sidebar-menu-rtl':'']">
                                    {{$t('global.AccountStatement')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end financial Accounts-->

                    <!--start complaints-->
                    <li class="submenu" v-if="permission.includes('Complaints')">
                        <a href="#" ><i class="fas fa-comment-dots"></i> <span> {{$t('global.Complaints')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <li
                                :class="[{'active': ['indexComplaints','createComplaints','editComplaints'].includes($route.name)}]"
                                v-if="permission.includes('Complaints read')"
                            >
                                <router-link :to="{name:'indexComplaint'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.Complaints')}}
                                </router-link>
                            </li>
                            <li
                                :class="[{'active': ['indexComplaintClient','showComplaintClient','editComplaint'].includes($route.name)}]"
                                v-if="permission.includes('ComplaintClients read')"
                            >
                                <router-link :to="{name:'indexComplaintClient'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.ComplaintClients')}}
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <!--end Complaints-->

                    <!--start Suggestions-->
                    <li class="submenu" v-if="permission.includes('Suggestions')">
                        <a href="#" ><i class="fas fa-comment-dots"></i> <span> {{$t('global.Suggestions')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <li
                                :class="[{'active': ['indexSuggestion','createSuggestion','editSuggestion'].includes($route.name)}]"
                                v-if="permission.includes('Suggestions read')"
                            >
                                <router-link :to="{name:'indexSuggestion'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.Suggestions')}}
                                </router-link>
                            </li>
                            <li
                                :class="[{'active': ['indexSuggestionClient','showSuggestionClient','editSuggestion'].includes($route.name)}]"
                                v-if="permission.includes('SuggestionClients read')"
                            >
                                <router-link :to="{name:'indexSuggestionClient'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.SuggestionClients')}}
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <!--end Suggestions-->

                    <!--start ads-->
                    <li class="submenu" v-if="permission.includes('ads')">
                        <a href="#" ><i class="fas fa-ad"></i> <span> {{$t('global.adsManegment')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['indexPopupAds','createPopupAds','editPopupAds'].includes($route.name)}]"
                                v-if="permission.includes('popupAds read')"
                            >
                                <router-link :to="{name:'indexPopupAds'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.popupAds')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexFirstSliderAds','createFirstSliderAds','editFirstSliderAds'].includes($route.name)}]"
                                v-if="permission.includes('firstSliderAds read')"
                            >
                                <router-link :to="{name:'indexFirstSliderAds'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.firstSliderAds')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexSecondSliderAds','createSecondSliderAds','editSecondSliderAds'].includes($route.name)}]"
                                v-if="permission.includes('secondSliderAds read')"
                            >
                                <router-link :to="{name:'indexSecondSliderAds'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.secondSliderAds')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end ads-->

                    <!--start area-->
                    <li class="submenu" v-if="permission.includes('areaManagement')">
                        <a href="#" ><i class="fas fa-city"></i> <span> {{$t('global.AreasManagement')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li
                                :class="[{'active': ['indexProvince','createProvince','editProvince'].includes($route.name)}]"
                                v-if="permission.includes('areas read')"
                            >
                                <router-link :to="{name:'indexProvince'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.Provinces')}}
                                </router-link>
                            </li>

                            <li
                                :class="[{'active': ['indexArea','createArea','editArea'].includes($route.name)}]"
                                v-if="permission.includes('areas read')"
                            >
                                <router-link :to="{name:'indexArea'}" :class="['sidebar-menu-rtl']">
                                    {{$t('global.areas')}}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end area-->

                    <!--start competitions-->
                    <li class="submenu">
                        <a href="#" ><i class="fas fa-boxes"></i> <span>إدارة المسابقات</span><span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>
                            <li class="submenu" v-if="permission.includes('CRM')">
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i  :class="['fa fa-home',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                    شير واكسب
                                    <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span>
                                </a>
                                <ul>
                                    <li
                                        :class="[{'active': ['indexCompetition','createCompetition','editCompetition'].includes($route.name)}]"
                                        v-if="permission.includes('competition read')"
                                    >
                                        <router-link :to="{name:'indexCompetition'}" :class="['sidebar-menu-rtl']">
                                            {{$t('global.Competition')}}
                                        </router-link>
                                    </li>

                                    <li
                                        :class="[{'active': ['indexShare'].includes($route.name)}]"
                                        v-if="permission.includes('share read')"
                                    >
                                        <router-link :to="{name:'indexShare'}" :class="['sidebar-menu-rtl']">
                                            {{$t('global.Share')}}
                                        </router-link>
                                    </li>
                                </ul>

                                <!--start wallet-->
                                <a  href="#" :class="['drop-child',this.$i18n.locale == 'ar'?'menu-arrow-ar ml-4':'padding-en']">
                                    <i  :class="['fa fa-home',this.$i18n.locale == 'ar'?'t-right':'']"></i>
                                   المحفظة
                                    <span :class="['menu-arrow',this.$i18n.locale == 'ar'?'menu-arrow-ar':'']"></span>
                                </a>
                                <ul>
                                    <li
                                        :class="[{'active': ['indexPointPrice','createPointPrice','editPointPrice'].includes($route.name)}]"
                                        v-if="permission.includes('pointPrice read')"
                                    >
                                        <router-link :to="{name:'indexPointPrice'}" :class="['sidebar-menu-rtl']">
                                            {{$t('global.pointPrice')}}
                                        </router-link>
                                    </li>

                                    <li
                                        :class="[{'active': ['indexPointWelcome','createPointWelcome','editPointWelcome'].includes($route.name)}]"
                                        v-if="permission.includes('pointWelcome read')"
                                    >
                                        <router-link :to="{name:'indexPointWelcome'}" :class="['sidebar-menu-rtl']">
                                            {{$t('global.pointWelcome')}}
                                        </router-link>
                                    </li>

                                    <li
                                        :class="[{'active': ['indexWallet'].includes($route.name)}]"
                                        v-if="permission.includes('wallet read')"
                                    >
                                        <router-link :to="{name:'indexWallet'}" :class="['sidebar-menu-rtl']">
                                            {{$t('global.wallet')}}
                                        </router-link>
                                    </li>
                                </ul>
                                <!--end wallet-->
                            </li>
                        </ul>
                    </li>
                    <!--end competitions-->

                    <!--start  report-->
                    <li class="submenu">
                        <a href="#" ><i class="fas fa-clipboard-list"></i> <span> {{$t('global.report')}}</span>  <span :class="['menu-arrow menu-arrow-ar']"></span></a>
                        <ul>

                            <li :class="[$route.name == 'productReport'? 'active': '']">
                                <router-link :to="{name:'productReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.productReport') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'categoryReport'? 'active': '']">
                                <router-link :to="{name:'categoryReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.categoryReport') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'clientSaleReport'? 'active': '']">
                                <router-link :to="{name:'clientSaleReport'}"  :class="['sidebar-menu-rtl']">
                                    {{ $t('global.clientOldNew') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'clientQtyReport'? 'active': '']">
                                <router-link :to="{name:'clientQtyReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.clientQty') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'clientPriceReport'? 'active': '']">
                                <router-link :to="{name:'clientPriceReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.clientPrice') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'storeReport'? 'active': '']">
                                <router-link :to="{name:'storeReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.storeReport') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'suggestionClient'? 'active': '']">
                                <router-link :to="{name:'suggestionClient'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.suggestionClient') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'totalPurchaseReport'? 'active': '']">
                                <router-link :to="{name:'totalPurchaseReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.totalPurchaseReport') }}
                                </router-link>
                            </li>

                            <li :class="[$route.name == 'totalOrderReport'? 'active': '']">
                                <router-link :to="{name:'totalOrderReport'}" :class="['sidebar-menu-rtl']">
                                    {{ $t('global.totalOrderReport') }}
                                </router-link>
                            </li>

                        </ul>
                    </li>
                    <!--end  report-->

                    <!--start knowledge-->
                    <li
                        :class="[{'active': ['indexKnowledge','createKnowledge','editKnowledge'].includes($route.name)}]"
                        v-if="permission.includes('CreditCapacity read')"
                    >
                        <router-link :to="{name:'indexKnowledge'}" >
                            <i class="fas fa-users"></i>
                            <span>{{ $t('sidebar.Knowledge') }}</span>
                        </router-link>
                    </li>
                    <!--end knowledge-->

                    <!--start notifications-->
                    <li
                        :class="[{'active': ['createNotification'].includes($route.name)}]"
                        v-if="permission.includes('addNotificationApp create')"
                    >
                        <router-link :to="{name:'createNotification'}" >
                            <i class="fas fa-bell"></i>
                            <span>{{ $t('global.addNotificationApp') }}</span>
                        </router-link>
                    </li>
                    <!--end notifications-->

                    <!--start discount-->
                    <li
                        :class="[{'active': ['indexDiscount','createDiscount','editDiscount'].includes($route.name)}]"
                        v-if="permission.includes('discount read')"
                    >
                        <router-link :to="{name:'indexDiscount'}" >
                            <i class="fas fa-percent"></i>
                            <span>{{ $t('sidebar.coupon') }}</span>
                        </router-link>
                    </li>
                    <!--end discount-->

                    <!--start offer-->
                    <li
                        :class="[{'active': ['indexDiscountOffer','createDiscountOffer','editDiscountOffer'].includes($route.name)}]"
                        v-if="permission.includes('discount read')"
                    >
                        <router-link :to="{name:'indexDiscountOffer'}" >
                            <i class="fas fa-percent"></i>
                            <span>{{ $t('sidebar.offerDiscount') }}</span>
                        </router-link>
                    </li>
                    <!--end offer-->

                    <!--start tax-->
                    <li
                        :class="[{'active': ['indexTax','createTax','editTax'].includes($route.name)}]"
                        v-if="permission.includes('tax read')"
                    >
                        <router-link :to="{name:'indexTax'}" >
                            <i class="fas fa-table"></i>
                            <span>{{ $t('sidebar.types_of_taxes') }}</span>
                        </router-link>
                    </li>
                    <!--end tax-->

                    <!--start credit capacity-->
                    <li
                        :class="[{'active': ['indexCreditCapacity','editCreditCapacity'].includes($route.name)}]"
                        v-if="permission.includes('CreditCapacity read')"
                    >
                        <router-link :to="{name:'indexCreditCapacity'}" >
                            <i class="fas fa-credit-card" aria-hidden="true"></i>
                            <span>{{ $t('global.CreditCapacity') }}</span>
                        </router-link>
                    </li>
                    <!--end credit capacity-->

                    <!--start companyProfile-->
                    <li
                        :class="[{'active': ['indexcompanyProfile','createcompanyProfile','editcompanyProfile'].includes($route.name)}]"
                        v-if="permission.includes('companyProfile read')"
                    >
                        <router-link :to="{name:'indexCompanyProfile'}" >
                            <i class="fas fa-cogs"></i>
                            <span>{{ $t('sidebar.companyProfile') }}</span>
                        </router-link>
                    </li>
                    <!--end companyProfile-->

                    <!--start termsPrivacy-->
                    <li
                        :class="[{'active': ['indexTermsPrivacy','createTermsPrivacy','editTermsPrivacy'].includes($route.name)}]"
                        v-if="permission.includes('termsPrivacy read')"
                    >
                        <router-link :to="{name:'indexTermsPrivacy'}" >
                            <i class="fas fa-cogs"></i>
                            <span>{{ $t('sidebar.termsPrivacy') }}</span>
                        </router-link>
                    </li>
                    <!--end termsPrivacy-->

                    <!--start setting-->
                    <li
                        :class="[{'active': ['editSetting','indexSetting'].includes($route.name)}]"
                        v-if="permission.includes('setting read')"
                    >
                        <router-link :to="{name:'indexSetting'}" >
                            <i class="fas fa-cogs"></i>
                            <span>{{ $t('sidebar.setting') }}</span>
                        </router-link>
                    </li>
                    <!--end setting-->

                    <!--start download Representative-->
                    <li>
                        <a download href="/web/mandob.apk" >
                            <i class="fas fa-motorcycle"></i>
                            <span>{{ $t('global.downloadRepresentative') }}</span>
                        </a>
                    </li>
                    <!--end download Representative-->

                </ul>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->
</template>

<script>
import {ref,onMounted,reactive,computed} from 'vue';
import {useStore} from 'vuex';

export default {
   setup(){

        let store = useStore();

        let permission = computed(() => store.getters['authAdmin/permission']);

        onMounted(() => {
            var $slimScrolls = $('.slimscroll');

            // Sidebar Slimscroll
            if ($slimScrolls.length > 0) {
                $slimScrolls.slimScroll({
                    height: 'auto',
                    width: '100%',
                    position: 'right',
                    size: '7px',
                    color: '#ccc',
                    allowPageScroll: false,
                    wheelStep: 10,
                    touchScrollStep: 100
                });
                var wHeight = $(window).height() - 60;
                $slimScrolls.height(wHeight);
                $('.sidebar .slimScrollDiv').height(wHeight);
                $(window).resize(function () {
                    var rHeight = $(window).height() - 60;
                    $slimScrolls.height(rHeight);
                    $('.sidebar .slimScrollDiv').height(rHeight);
                });
            }
        });


        return {permission};
   }
}


window.onload = (event) => {
    var Sidemenu = function () {
        this.$menuItem = $('#sidebar-menu a');
    };
    function init() {
    var $this = Sidemenu;
        $('#sidebar-menu a').on('click', function (e) {
            if ($(this).parent().hasClass('submenu')) {
                e.preventDefault();
            }
            if (!$(this).hasClass('subdrop')) {
                $('ul', $(this).parents('ul:first')).slideUp(350);
                $('a', $(this).parents('ul:first')).removeClass('subdrop');
                $(this).next('ul').slideDown(350);
                $(this).addClass('subdrop');
            } else if ($(this).hasClass('subdrop')) {
                $(this).removeClass('subdrop');
                $(this).next('ul').slideUp(350);
            }
        });
        $('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
    }

// Sidebar Initiate
    init();

};

</script>

<style>
.sidebar-ar {
    left: unset;
    right: 0;
}
.sidebar .sidebar-menu > ul > li > a span {
    margin-right: 10px;
}
.sidebar-menu ul ul a:hover {
    background-color: #000 !important;
    color: #FFF;
}
.sidebar-menu li a{
    color: #FFF;
}

.sidebar-menu li a i{
    color: #FFF !important;
}


.sidebar-menu li.active > a{
    color: #fff !important;
    background-color: #000 !important;
}

.menu-title {
    color: #fcb00c !important;
}

.show{
    display: block;
}
.sidebar{
    background-color: #103C67;
}

.sidebar-menu .menu-arrow.menu-arrow-ar{
    left: 15px;
    right: unset;
}
.sidebar-menu-rtl{
    padding: 7px 45px 7px 10px!important;
}
.padding-en{
    padding: 7px 10px 7px 32px !important;
}
.drop-child{
    padding: none !important;
}
.drop-child span{
    float: none !important;
}
.t-right{
    text-align: right !important;
}

</style>
