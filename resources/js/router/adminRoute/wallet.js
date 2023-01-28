import indexWallet from "../../view/admin/wallet/index";
import store from "../../store/admin";

export default [
    {
        path: 'wallet',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexWallet',
                component: indexWallet,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('wallet read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
