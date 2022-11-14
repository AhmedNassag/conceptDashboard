import indexCapitalOwnerAccount from "../../view/admin/capitalOwnerAccount/index";
import createCapitalOwnerAccount from "../../view/admin/capitalOwnerAccount/create";
import editCapitalOwnerAccount from "../../view/admin/capitalOwnerAccount/edit";
import store from "../../store/admin";

export default [
    {
        path: 'capitalOwnerAccount',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCapitalOwnerAccount',
                component: indexCapitalOwnerAccount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('CapitalOwnerAccount read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createCapitalOwnerAccount',
                component: createCapitalOwnerAccount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('CapitalOwnerAccount create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editCapitalOwnerAccount',
                component: editCapitalOwnerAccount,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('CapitalOwnerAccount edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
