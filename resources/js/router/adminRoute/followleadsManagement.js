import indexfollowLeadsManagement from "../../view/admin/followleadsManagement/index";
import createfollowLeadsManagement from "../../view/admin/followleadsManagement/create";
import editfollowLeadsManagement from "../../view/admin/followleadsManagement/edit";
import followChangeEmployee from "../../view/admin/followleadsManagement/changeEmployee";
import store from "../../store/admin";

export default [
    {
        path: 'followleadsManagement',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexfollowLeadsManagement',
                component: indexfollowLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeadsManagement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createfollowLeadsManagement',
                component: createfollowLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeadsManagement create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editfollowLeadsManagement',
                component: editfollowLeadsManagement,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeadsManagement edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'followchangeEmployee/:id(\\d+)',
                name: 'followChangeEmployee',
                component: followChangeEmployee,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeadsManagement changeEmployee')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
