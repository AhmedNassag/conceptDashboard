import indexRepresentative from "../../view/admin/representative/index";
import createRepresentative from "../../view/admin/representative/create";
import editRepresentative from "../../view/admin/representative/edit";
import changePasswordRepresentative from "../../view/admin/representative/changePassword";
import store from "../../store/admin";

export default [
    {
        path: 'representative',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexRepresentative',
                component: indexRepresentative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createRepresentative',
                component: createRepresentative,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editRepresentative',
                component: editRepresentative,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representative edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'changePassword/:id(\\d+)',
                name: 'changePasswordRepresentative',
                component: changePasswordRepresentative,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('representativeChangePassword edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
