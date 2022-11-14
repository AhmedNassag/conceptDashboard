import indexPurchaseExpenses from "../../view/admin/purchaseExpenses/index";
import store from "../../store/admin";

export default [
    {
        path: 'purchaseExpenses',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchaseExpenses',
                component: indexPurchaseExpenses,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchaseExpenses read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
