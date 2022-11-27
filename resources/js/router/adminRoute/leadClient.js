import indexLeadClient from "../../view/admin/leadClient/index";
import createLeadClient from "../../view/admin/leadClient/create";
import editLeadClient from "../../view/admin/leadClient/edit";
import store from "../../store/admin";

export default [
    {
        path: 'leadClient',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadClient',
                component: indexLeadClient,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('measureUnit read')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
            {
                path: 'create',
                name: 'createLeadClient',
                component: createLeadClient,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('measureUnit create')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editLeadClient',
                component: editLeadClient,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    // if(permission.includes('measureUnit edit')){
                        return next();
                    // }else{
                    //     return next({name:'Page404'});
                    // }
                }
            },
        ]
    },
];
