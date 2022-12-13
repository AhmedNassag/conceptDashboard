import indexProblemTargetPlan from "../../view/admin/problemtargetPlan/index";

import indexProblemTarget from "../../view/admin/problemtarget/index";
import createProblemTarget from "../../view/admin/problemtarget/create";
import editProblemTarget from "../../view/admin/problemtarget/edit";
import store from "../../store/admin";

export default [
    {
        path: 'problemtargetPlan',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProblemTargetPlan',
                component: indexProblemTargetPlan,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemtargetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'problemtarget/:id(\\d+)',
                name: 'indexProblemTarget',
                component: indexProblemTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemtargetPlan read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/problemtarget/:id(\\d+)',
                name: 'createProblemTarget',
                component: createProblemTarget,
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
                path: 'edit/:idPlan(\\d+)/problemtarget/:idTarget(\\d+)',
                name: 'editProblemTarget',
                component: editProblemTarget,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemtargetPlan edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
