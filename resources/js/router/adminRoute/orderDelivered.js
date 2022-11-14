import indexOrderDelivered from "../../view/admin/orderDelivered/index";
import showOrderDelivered from "../../view/admin/orderDelivered/show";
import store from "../../store/admin";

export default [
    {
        path: 'orderDelivered',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderDelivered',
                component: indexOrderDelivered,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderDelivered read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showOrderDelivered',
                component: showOrderDelivered,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderDelivered read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
