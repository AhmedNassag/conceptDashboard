import indexMerchant from "../../view/admin/merchant/index";
import createMerchant  from "../../view/admin/merchant/create";
import editMerchant  from "../../view/admin/merchant/edit";
import showMerchant from "../../view/admin/merchant/show";
import store from "../../store/admin";

export default [
    {
        path: 'merchant',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexMerchant',
                component: indexMerchant,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createMerchant',
                component: createMerchant,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editMerchant',
                component: editMerchant,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showMerchant',
                component: showMerchant,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
