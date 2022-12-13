import indexLeadsManagement from "../../view/admin/leadsManagement/index";
import createLeadsManagement from "../../view/admin/leadsManagement/create";
import editLeadsManagement from "../../view/admin/leadsManagement/edit";
import ChangeEmployee from "../../view/admin/leadsManagement/changeEmployee";
import store from "../../store/admin";

export default [
    {
        path: 'leadsManagement',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadsManagement',
                component: indexLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('LeadsManagement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createLeadsManagement',
                component: createLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('LeadsManagement create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editLeadsManagement',
                component: editLeadsManagement,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('LeadsManagement edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'changeEmployee/:id(\\d+)',
                name: 'ChangeEmployee',
                component: ChangeEmployee,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('LeadsManagement changeEmployee')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
