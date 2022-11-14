import indexSupplierAccountStatement from "../../view/admin/supplierAccountStatement/index";
import store from "../../store/admin";

export default [
    {
        path: 'supplierAccountStatement',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSupplierAccountStatement',
                component: indexSupplierAccountStatement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('supplierAccountStatement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
