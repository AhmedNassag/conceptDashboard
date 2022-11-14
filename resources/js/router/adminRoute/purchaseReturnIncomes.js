import indexPurchaseReturnIncomes from "../../view/admin/purchaseReturnIncomes/index";
import store from "../../store/admin";

export default [
    {
        path: 'purchaseReturnIncomes',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPurchaseReturnIncomes',
                component: indexPurchaseReturnIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('purchaseReturnIncomes read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
