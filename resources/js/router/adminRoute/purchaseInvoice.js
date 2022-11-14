import indexPurchaseInvoice from "../../view/admin/purchaseInvoice/index";
import createPurchaseInvoice from "../../view/admin/purchaseInvoice/create";
import editPurchaseInvoice from "../../view/admin/purchaseInvoice/edit";
import store from "../../store/admin";

export default [
    {
        path: 'purchaseInvoice',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchaseInvoice',
                component: indexPurchaseInvoice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('PurchaseInvoice read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPurchaseInvoice',
                component: createPurchaseInvoice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('PurchaseInvoice create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPurchaseInvoice',
                component: editPurchaseInvoice,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('PurchaseInvoice edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
