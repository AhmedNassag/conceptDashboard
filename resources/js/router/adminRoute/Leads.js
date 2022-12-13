import indexLeadSalesHome from "../../view/admin/Lead/home";

import indexLead from "../../view/admin/Lead/index";
import createLead from "../../view/admin/Lead/create";
import editLead from "../../view/admin/Lead/edit";

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
                path: 'lead/:id(\\d+)',
                name: 'indexLead',
                component: indexLead,
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
                path: 'create/lead/:id(\\d+)',
                name: 'createLead',
                component: createLead,
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
                name: 'editLead',
                component: editLead,
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
