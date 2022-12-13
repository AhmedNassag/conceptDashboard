import indexTargetPlan from "../../view/admin/targetPlan/index";

import indexTarget from "../../view/admin/target/index";
import createTarget from "../../view/admin/target/create";
import editTarget from "../../view/admin/target/edit";
import store from "../../store/admin";

export default [
    {
        path: 'targetPlan',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTargetPlan',
                component: indexTargetPlan,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('targetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'target/:id(\\d+)',
                name: 'indexTarget',
                component: indexTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('targetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/target/:id(\\d+)',
                name: 'createTarget',
                component: createTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('targetPlan create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idPlan(\\d+)/target/:idTarget(\\d+)',
                name: 'editTarget',
                component: editTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('targetPlan edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
