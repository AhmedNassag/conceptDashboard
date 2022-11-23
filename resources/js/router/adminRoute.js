import {createRouter, createWebHistory} from 'vue-router';
import Index from '../view/admin/Dashboard.vue';
import department from './adminRoute/department';
import supplier from './adminRoute/supplier';
import category from './adminRoute/category';
import company from './adminRoute/company';
import job from './adminRoute/job';
import filterWax from './adminRoute/filterWax';
import role from './adminRoute/role';
import employee from './adminRoute/employee';
import measure from './adminRoute/measure';
import discount from './adminRoute/discount';
import tax from './adminRoute/tax';
import offerDiscount from './adminRoute/discount&offer';
import representative from './adminRoute/representative';
import province from './adminRoute/province';
import area from './adminRoute/area';
import stores from './adminRoute/store';
import product from './adminRoute/product';
import client from './adminRoute/client';
import Assets from "./adminRoute/Assets";
import orderDirect from './adminRoute/orderDirect';
import sellingMethod from './adminRoute/sellingMethod';
import creditCapacity from './adminRoute/creditCapacity';
import purchaseInvoice from './adminRoute/purchaseInvoice';
import examinationRecord from './adminRoute/examinationRecord';
import purchaseReturn from './adminRoute/purchaseReturn';
import ProductsPricing from './adminRoute/ProductPricing';
import earnedDiscount from './adminRoute/earnedDiscount';
import notification from '../view/admin/notifications';
import Page404 from '../view/admin/Page404.vue';
import middlewarePipeline from "./middlewarePipeline";
import store from "../store/admin";
import guest from "../middleware/admin/guest";
import checkToken from "../middleware/admin/checkToken";
import auth from "../middleware/admin/auth";
import login from "../view/admin/login";
import forgetPassword from "../view/admin/forgetPassword";
import resetPassword from "../view/admin/resetPassword";
import subCategory from "./adminRoute/subCategory";
import Opponents from "./adminRoute/Opponents";
import ExpenseAccounts from "./adminRoute/ExpenseAccounts";
import IncomeAccounts from "./adminRoute/IncomeAccounts";
import dailyRestriction from "./adminRoute/dailyRestriction";
import trialBalance from "./adminRoute/trialBalance";
import financialCenter from "./adminRoute/financialCenter";
import incomeList from "./adminRoute/incomeList";
import AccountStatement from "./adminRoute/AccountStatement";
import capitalOwnerAccount from "./adminRoute/capitalOwnerAccount";
import popupAds from "./adminRoute/popupAds";
import firstSliderAds from "./adminRoute/firstSliderAds";
import secondSliderAds from "./adminRoute/secondSliderAds";
import setting from "./adminRoute/setting";
import expense from "./adminRoute/expense";
import income from "./adminRoute/income";
import incomeAndExpense from "./adminRoute/incomeAndExpense";
import transferringTreasury from "./adminRoute/transferringTreasury";
import treasuriesExpense from "./adminRoute/treasuriesExpense";
import treasuriesIncome from "./adminRoute/treasuriesIncome";
import saleReport from "./adminRoute/saleReport";
import CategoryProductReport from "./adminRoute/CategoryProductReport";
import treasury from "./adminRoute/treasury";
import suggestion from "./adminRoute/suggestion";
import clientReport from "./adminRoute/clientReport";
import suggestionClient from "./adminRoute/suggestionClient";
import areaReport from "./adminRoute/storeReport";
import purchaseExpenses from "./adminRoute/purchaseExpenses";
import supplierExpenses from "./adminRoute/supplierExpenses";
import clientExpenses from "./adminRoute/clientExpenses";
import supplierIncomes from "./adminRoute/supplierIncomes";
import clientIncome from "./adminRoute/clientIncome";
import purchaseReturnIncomes from "./adminRoute/purchaseReturnIncomes";
import orderOnline from "./adminRoute/orderOnline";
import orderReturned from "./adminRoute/orderReturned";
import orderDelivered from "./adminRoute/orderDelivered";
import sparePart from "./adminRoute/sparePart";
import orderIncomes from "./adminRoute/orderIncomes";
import SupplierAccountStatement from "./adminRoute/SupplierAccountStatement";
import ClientAccountStatement from "./adminRoute/ClientAccountStatement";
import addNotificationApp from "./adminRoute/addNotificationApp";
import knowledgeWay from "./adminRoute/knowledgeWay";
import userCompany from "./adminRoute/userCompany";
import merchant from "./adminRoute/merchant";
import leadFollowUps from "./adminRoute/leadFollowUps";
import leadSourse from "./adminRoute/leadSourse";
import lead from "./adminRoute/lead";




const routes = [
    {
        path: '/',
        name: 'loginLang',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/forget-password',
        name: 'forgetPassword',
        component: forgetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        component: resetPassword,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: '/dashboard',
        name: 'partner',
        component: {
            template: '<router-view />',
        },
        meta: {
            middleware: [auth, checkToken]
        },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Index,
            },
            {
                path: 'notification',
                name: 'notification',
                component: notification,
            },
            ...department,
            ...job,
            ...employee,
            ...role,
            ...supplier,
            ...category,
            ...company,
            ...measure,
            ...discount,
            ...tax,
            ...sellingMethod,
            ...province,
            ...area,
            ...creditCapacity,
            ...representative,
            ...purchaseInvoice,
            ...examinationRecord,
            ...purchaseReturn,
            ...ProductsPricing,
            ...earnedDiscount,
            ...product,
            ...stores,
            ...subCategory,
            ...offerDiscount,
            ...client,
            ...Assets,
            ...Opponents,
            ...ExpenseAccounts,
            ...IncomeAccounts,
            ...dailyRestriction,
            ...trialBalance,
            ...financialCenter,
            ...incomeList,
            ...AccountStatement,
            ...orderDirect,
            ...capitalOwnerAccount,
            ...popupAds,
            ...firstSliderAds,
            ...secondSliderAds,
            ...setting,
            ...expense,
            ...income,
            ...incomeAndExpense,
            ...transferringTreasury,
            ...treasuriesExpense,
            ...treasuriesIncome,
            ...treasury,
            ...saleReport,
            ...CategoryProductReport,
            ...clientReport,
            ...suggestion,
            ...suggestionClient,
            ...areaReport,
            ...purchaseExpenses,
            ...supplierExpenses,
            ...clientExpenses,
            ...supplierIncomes,
            ...clientIncome,
            ...purchaseReturnIncomes,
            ...orderOnline,
            ...orderReturned,
            ...orderDelivered,
            ...orderIncomes,
            ...SupplierAccountStatement,
            ...ClientAccountStatement,
            ...addNotificationApp,
            ...filterWax,
            ...sparePart,
            ...knowledgeWay,
            ...userCompany,
            ...merchant,
            ...leadFollowUps,
            ...leadSourse,
            ...lead
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'Page404',
        component: Page404
    },
];

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes
});


router.beforeEach((to, from, next) => {

    if (!to.meta.middleware) return next();
    const middleware = to.meta.middleware;
    const context = {
        to,
        from,
        next,
        store
    };
    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    });
});

export default router;
