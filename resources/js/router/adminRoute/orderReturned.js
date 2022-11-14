import indexOrderReturned from "../../view/admin/orderReturned/index";
import showOrderReturned from "../../view/admin/orderReturned/show";
import store from "../../store/admin";

export default [
    {
        path: 'orderReturned',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderReturned',
                component: indexOrderReturned,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderReturned read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showOrderReturned',
                component: showOrderReturned,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderReturned read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
