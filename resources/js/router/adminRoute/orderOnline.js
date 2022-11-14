import indexOrderOnline from "../../view/admin/orderOnline/index";
import editOrderOnline from "../../view/admin/orderOnline/edit";
import showOrderOnline from "../../view/admin/orderOnline/show";
import store from "../../store/admin";
import showSuggestionClient from "../../view/admin/suggestionClient/show";

export default [
    {
        path: 'orderOnline',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderOnline',
                component: indexOrderOnline,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderOnline read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editOrderOnline',
                component: editOrderOnline,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderOnline edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showOrderOnline',
                component: showOrderOnline,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderOnline read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
