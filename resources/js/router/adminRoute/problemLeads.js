import indexProblemLeadSalesHome from "../../view/admin/problemLead/home";

import indexProblemLead from "../../view/admin/problemLead/index";
import createProblemLead from "../../view/admin/problemLead/create";
import editProblemLead from "../../view/admin/problemLead/edit";

import indexProblemLeadComment from "../../view/admin/problemLeadComment/index";
import createProblemLeadComment from "../../view/admin/problemLeadComment/create";
import editProblemLeadComment from "../../view/admin/problemLeadComment/edit";
import store from "../../store/admin";

export default [
    {
        path: 'problemLeadsSales',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProblemLeadSalesHome',
                component: indexProblemLeadSalesHome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'problemlead/:id(\\d+)',
                name: 'indexProblemLead',
                component: indexProblemLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/problemlead/:id(\\d+)',
                name: 'createProblemLead',
                component: createProblemLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/problemlead/:idLead(\\d+)',
                name: 'editProblemLead',
                component: editProblemLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },


            //start comment

            {
                path: 'problemcomments/:id(\\d+)',
                name: 'indexProblemLeadComment',
                component: indexProblemLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/problemcomments/:id(\\d+)',
                name: 'createProblemLeadComment',
                component: createProblemLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:idTarget(\\d+)/problemcomments/:idLead(\\d+)',
                name: 'editProblemLeadComment',
                component: editProblemLeadComment,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeads edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
