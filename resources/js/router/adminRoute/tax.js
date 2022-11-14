import indexTax from "../../view/admin/tax/index";
import createTax from "../../view/admin/tax/create";
import editTax from "../../view/admin/tax/edit";
import store from "../../store/admin";

export default [
    {
        path: 'tax',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTax',
                component: indexTax,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('tax read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createTax',
                component: createTax,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('tax create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editTax',
                component: editTax,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('tax edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
