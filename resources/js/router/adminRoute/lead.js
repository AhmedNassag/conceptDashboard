import indexLead from "../../view/admin/lead/index";
import createLead from "../../view/admin/lead/create";
import editLead from "../../view/admin/lead/edit";
import showLead from "../../view/admin/lead/show";
import store from "../../store/admin";

export default [
    {
        path: 'lead',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLead',
                component: indexLead,
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
                name: 'createLead',
                component: createLead,
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
                name: 'editLead',
                component: editLead,
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
            {
                path: 'show/:id(\\d+)',
                name: 'showLead',
                component: showLead,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
