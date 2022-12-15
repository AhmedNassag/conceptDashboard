import indexfollowTargetAchievedHome from "../../view/admin/followTargetAchived/home";

import indexfollowTargetAchieved from "../../view/admin/followTargetAchived/index";
import createfollowTargetAchieved from "../../view/admin/followTargetAchived/create";
import editfollowTargetAchieved from "../../view/admin/followTargetAchived/edit";

import store from "../../store/admin";

export default [
    {
        path: 'followTargetAchieved',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexfollowTargetAchievedHome',
                component: indexfollowTargetAchievedHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followTargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'followlead/:id(\\d+)',
                name: 'indexfollowTargetAchieved',
                component: indexfollowTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followTargetAchieved read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/followlead/:id(\\d+)',
                name: 'createfollowTargetAchieved',
                component: createfollowTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followTargetAchieved create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/followlead/:idLead(\\d+)',
                name: 'editfollowTargetAchieved',
                component: editfollowTargetAchieved,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followTargetAchieved edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
