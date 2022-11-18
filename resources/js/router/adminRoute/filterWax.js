import indexFilterWax from "../../view/admin/filterWax/index";
import createFilterWax from "../../view/admin/filterWax/create";
import editFilterWax from "../../view/admin/filterWax/edit";
import store from "../../store/admin";

export default [
    {
        path: 'filterWax',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexFilterWax',
                component: indexFilterWax,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createFilterWax',
                component: createFilterWax,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editFilterWax',
                component: editFilterWax,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
