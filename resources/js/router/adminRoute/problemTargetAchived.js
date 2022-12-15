import indexProblemTargetAchievedHome from "../../view/admin/problemTargetAchived/home";

import indexProblemTargetAchieved from "../../view/admin/problemTargetAchived/index";
import createProblemTargetAchieved from "../../view/admin/problemTargetAchived/create";
import editProblemTargetAchieved from "../../view/admin/problemTargetAchived/edit";

import store from "../../store/admin";

export default [
    {
        path: 'problemTargetAchieved',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProblemTargetAchievedHome',
                component: indexProblemTargetAchievedHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemTargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'problemlead/:id(\\d+)',
                name: 'indexProblemTargetAchieved',
                component: indexProblemTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemTargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/problemlead/:id(\\d+)',
                name: 'createProblemTargetAchieved',
                component: createProblemTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemTargetAchieved create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/problemlead/:idLead(\\d+)',
                name: 'editProblemTargetAchieved',
                component: editProblemTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemTargetAchieved edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
