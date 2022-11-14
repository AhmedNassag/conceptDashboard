import indexAssets from "../../view/admin/Assets/index";
import createAssets from "../../view/admin/Assets/create";
import editAssets from "../../view/admin/Assets/edit";
import indexSubAssets from "../../view/admin/subAssets/index";
import createSubAssets from "../../view/admin/subAssets/create";
import editSubAssets from "../../view/admin/subAssets/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Assets',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexAssets',
                component: indexAssets,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createAssets',
                component: createAssets,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editAssets',
                component: editAssets,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },{
                path: 'subAssets/:id(\\d+)/main/:mainId(\\d+)',
                name: 'indexSubAssets',
                component: indexSubAssets,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'subAssets/create/:id(\\d+)/main/:mainId(\\d+)',
                name: 'createSubAssets',
                component: createSubAssets,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'subAssets/edit/:id(\\d+)/main/:mainId(\\d+)',
                name: 'editSubAssets',
                component: editSubAssets,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
