import indexUserCompany from "../../view/admin/userCompany/index";
import createUserCompany  from "../../view/admin/userCompany/create";
import editUserCompany  from "../../view/admin/userCompany/edit";
import showUserCompany  from "../../view/admin/userCompany/show";
import store from "../../store/admin";

export default [
    {
        path: 'userCompany',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexUserCompany',
                component: indexUserCompany,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createUserCompany',
                component: createUserCompany,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editUserCompany',
                component: editUserCompany,
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
            {
                path: 'show/:id(\\d+)',
                name: 'showUserCompany',
                component: showUserCompany,
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
