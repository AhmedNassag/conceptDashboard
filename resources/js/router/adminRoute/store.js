import indexStore from "../../view/admin/store/index";
import createStore  from "../../view/admin/store/create";
import editStore  from "../../view/admin/store/edit";
import showStore  from "../../view/admin/store/show";
import store from "../../store/admin";

export default [
    {
        path: 'store',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexStore',
                component: indexStore ,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('store read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createStore',
                component: createStore ,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('store create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editStore',
                component: editStore ,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('store edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },

            {
                path: 'showProduct/:id(\\d+)',
                name: 'showStore',
                component: showStore ,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('store read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
