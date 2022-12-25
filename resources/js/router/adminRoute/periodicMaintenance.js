import indexPeriodicMaintenance from "../../view/admin/periodicMaintenance/index";
import createPeriodicMaintenance from "../../view/admin/periodicMaintenance/create";
import editPeriodicMaintenance from "../../view/admin/periodicMaintenance/edit";
import nearPeriodicMaintenance from "../../view/admin/periodicMaintenance/near";
import delayPeriodicMaintenance from "../../view/admin/periodicMaintenance/delay";
import confirmPeriodicMaintenance from "../../view/admin/periodicMaintenance/confirm";
import store from "../../store/admin";

export default [
    {
        path: 'periodicMaintenance',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPeriodicMaintenance',
                component: indexPeriodicMaintenance,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPeriodicMaintenance',
                component: createPeriodicMaintenance,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPeriodicMaintenance',
                component: editPeriodicMaintenance,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'near',
                name: 'nearPeriodicMaintenance',
                component: nearPeriodicMaintenance,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'delay/:id(\\d+)',
                name: 'delayPeriodicMaintenance',
                component: delayPeriodicMaintenance,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'confirm/:id(\\d+)',
                name: 'confirmPeriodicMaintenance',
                component: confirmPeriodicMaintenance,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('periodicMaintenance edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
