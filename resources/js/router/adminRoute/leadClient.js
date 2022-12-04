import indexLeadClient from "../../view/admin/leadClient/index";
import createLeadClient from "../../view/admin/leadClient/create";
import addActionLeadClient from "../../view/admin/leadClient/addAction";
import changeLeadClient from "../../view/admin/leadClient/changeLeadClient";
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
                path: 'addAction/:id(\\d+)',
                name: 'addActionLeadClient',
                component: addActionLeadClient,
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
            {
                path: 'changeLeadClient/:id(\\d+)',
                name: 'changeLeadClient',
                component: changeLeadClient,
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
