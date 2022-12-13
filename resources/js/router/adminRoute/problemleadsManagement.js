import indexProblemLeadsManagement from "../../view/admin/problemleadsManagement/index";
import createProblemLeadsManagement from "../../view/admin/problemleadsManagement/create";
import editProblemLeadsManagement from "../../view/admin/problemleadsManagement/edit";
import ProblemChangeEmployee from "../../view/admin/problemleadsManagement/changeEmployee";
import store from "../../store/admin";

export default [
    {
        path: 'problemleadsManagement',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProblemLeadsManagement',
                component: indexProblemLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeadsManagement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createProblemLeadsManagement',
                component: createProblemLeadsManagement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeadsManagement create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editProblemLeadsManagement',
                component: editProblemLeadsManagement,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeadsManagement edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'problemchangeEmployee/:id(\\d+)',
                name: 'ProblemChangeEmployee',
                component: ProblemChangeEmployee,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemLeadsManagement changeEmployee')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
