import storeReport from "../../view/admin/storeReport/storePrice";
import store from "../../store/admin";

export default [
    {
        path: '/storeReport',
        name: 'storeReport',
        component: storeReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category read')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    }
];
