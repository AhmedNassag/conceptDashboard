import clientSaleReport from "../../view/admin/clientReport/clientNewOld";
import clientQtyReport from "../../view/admin/clientReport/clientQty";
import clientPriceReport from "../../view/admin/clientReport/clientPrice";
import suggestionClient from "../../view/admin/suggestionReport/suggestionClient";
import totalPurchaseReport from "../../view/admin/purchaseReport/totalPurchase";
import totalOrderReport from "../../view/admin/orderReport/totalOrder";
import store from "../../store/admin";

export default [
    {
        path: '/clientSaleReport',
        name: 'clientSaleReport',
        component: clientSaleReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category read')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: '/clientQtyReport',
        name: 'clientQtyReport',
        component: clientQtyReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: '/clientPriceReport',
        name: 'clientPriceReport',
        component: clientPriceReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: '/suggestionClient',
        name: 'suggestionClient',
        component: suggestionClient,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: '/totalPurchaseReport',
        name: 'totalPurchaseReport',
        component: totalPurchaseReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    },
    {
        path: '/totalOrderReport',
        name: 'totalOrderReport',
        component: totalOrderReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    }
];
