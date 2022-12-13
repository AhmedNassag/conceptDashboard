import indexfollowLeadSalesHome from "../../view/admin/followLead/home";

import indexfollowLead from "../../view/admin/followLead/index";
import createfollowLead from "../../view/admin/followLead/create";
import editfollowLead from "../../view/admin/followLead/edit";

import indexfollowLeadComment from "../../view/admin/followLeadComment/index";
import createfollowLeadComment from "../../view/admin/followLeadComment/create";
import editfollowLeadComment from "../../view/admin/followLeadComment/edit";
import store from "../../store/admin";

export default [
    {
        path: 'followLeadsSales',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexfollowLeadSalesHome',
                component: indexfollowLeadSalesHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'followlead/:id(\\d+)',
                name: 'indexfollowLead',
                component: indexfollowLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/followlead/:id(\\d+)',
                name: 'createfollowLead',
                component: createfollowLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/followlead/:idLead(\\d+)',
                name: 'editfollowLead',
                component: editfollowLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },


            //start comment

            {
                path: 'followcomments/:id(\\d+)',
                name: 'indexfollowLeadComment',
                component: indexfollowLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/followcomments/:id(\\d+)',
                name: 'createfollowLeadComment',
                component: createfollowLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/followcomments/:idLead(\\d+)',
                name: 'editfollowLeadComment',
                component: editfollowLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followLeads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
