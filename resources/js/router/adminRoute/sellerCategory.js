import indexSellerCategory from "../../view/admin/SellerCategory/index";
import createSellerCategory from "../../view/admin/SellerCategory/create";
import editSellerCategory from "../../view/admin/SellerCategory/edit";
import store from "../../store/admin";

export default [
    {
        path: 'SellerCategory',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSellerCategory',
                component: indexSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SellerCategory read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSellerCategory',
                component: createSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SellerCategory create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSellerCategory',
                component: editSellerCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SellerCategory edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
