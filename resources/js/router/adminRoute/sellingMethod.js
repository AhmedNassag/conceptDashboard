import indexSellingMethod from "../../view/admin/sellingMethod/index";
import createSellingMethod from "../../view/admin/sellingMethod/create";
import editSellingMethod from "../../view/admin/sellingMethod/edit";
import store from "../../store/admin";

export default [
    {
        path: 'sellingMethod',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSellingMethod',
                component: indexSellingMethod,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSellingMethod',
                component: createSellingMethod,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSellingMethod',
                component: editSellingMethod,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
