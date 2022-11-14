import indexClientAccountStatement from "../../view/admin/clientAccountStatement/index";
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
                name: 'indexClientAccountStatement',
                component: indexClientAccountStatement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientAccountStatement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
