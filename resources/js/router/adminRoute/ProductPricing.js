import indexProductsPricing from "../../view/admin/productPricing/index";
import store from "../../store/admin";

export default [
    {
        path: 'ProductsPricing',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProductsPricing',
                component: indexProductsPricing,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('ProductsPricing read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
