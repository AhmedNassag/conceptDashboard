import indexOrderDirect from "../../view/admin/orderDirect/index";
import createOrderDirect from "../../view/admin/orderDirect/create";
import editOrderDirect from "../../view/admin/orderDirect/edit";
import store from "../../store/admin";

export default [
    {
        path: 'orderDirect',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderDirect',
                component: indexOrderDirect,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('order read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createOrderDirect',
                component: createOrderDirect,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('order create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editOrderDirect',
                component: editOrderDirect,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('order edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
