import indexLeadFollowUps from "../../view/admin/leadFollowUps/index";
import createLeadFollowUps from "../../view/admin/leadFollowUps/create";
import editLeadFollowUps from "../../view/admin/leadFollowUps/edit";
import store from "../../store/admin";

export default [
    {
        path: 'leadFollowUps',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadFollowUps',
                component: indexLeadFollowUps,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createLeadFollowUps',
                component: createLeadFollowUps,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editLeadFollowUps',
                component: editLeadFollowUps,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
