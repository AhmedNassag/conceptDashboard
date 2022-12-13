import indexLeadSalesHome from "../../view/admin/leads/home";

import indexLeads from "../../view/admin/leads/index";
import createLeads from "../../view/admin/leads/create";
import editLeads from "../../view/admin/leads/edit";

import indexLeadComment from "../../view/admin/LeadComment/index";
import createLeadComment from "../../view/admin/LeadComment/create";
import editLeadComment from "../../view/admin/LeadComment/edit";
import store from "../../store/admin";

export default [
    {
        path: 'LeadsSales',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadSalesHome',
                component: indexLeadSalesHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'leads/:id(\\d+)',
                name: 'indexLeads',
                component: indexLeads,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/leads/:id(\\d+)',
                name: 'createLeads',
                component: createLeads,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/lead/:idLead(\\d+)',
                name: 'editLeads',
                component: editLeads,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },


            //start comment

            {
                path: 'comments/:id(\\d+)',
                name: 'indexLeadComment',
                component: indexLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/comments/:id(\\d+)',
                name: 'createLeadComment',
                component: createLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/comments/:idLead(\\d+)',
                name: 'editLeadComment',
                component: editLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Leads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
