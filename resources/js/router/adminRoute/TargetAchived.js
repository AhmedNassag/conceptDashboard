import indexTargetAchievedHome from "../../view/admin/TargetAchived/home";

import indexTargetAchieved from "../../view/admin/TargetAchived/index";
import createTargetAchieved from "../../view/admin/TargetAchived/create";
import editTargetAchieved from "../../view/admin/TargetAchived/edit";

import store from "../../store/admin";

export default [
    {
        path: 'TargetAchieved',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTargetAchievedHome',
                component: indexTargetAchievedHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('TargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'lead/:id(\\d+)',
                name: 'indexTargetAchieved',
                component: indexTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('TargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/lead/:id(\\d+)',
                name: 'createTargetAchieved',
                component: createTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('TargetAchieved create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/lead/:idLead(\\d+)',
                name: 'editTargetAchieved',
                component: editTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('TargetAchieved edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
