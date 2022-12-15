import indexfollowTargetPlan from "../../view/admin/followtargetPlan/index";

import indexfollowTarget from "../../view/admin/followtarget/index";
import createfollowTarget from "../../view/admin/followtarget/create";
import editfollowTarget from "../../view/admin/followtarget/edit";
import store from "../../store/admin";

export default [
    {
        path: 'followtargetPlan',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexfollowTargetPlan',
                component: indexfollowTargetPlan,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followtargetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'followtarget/:id(\\d+)',
                name: 'indexfollowTarget',
                component: indexfollowTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followtargetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/followtarget/:id(\\d+)',
                name: 'createfollowTarget',
                component: createfollowTarget,
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
                path: 'edit/:idPlan(\\d+)/followtarget/:idTarget(\\d+)',
                name: 'editfollowTarget',
                component: editfollowTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followtargetPlan edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
