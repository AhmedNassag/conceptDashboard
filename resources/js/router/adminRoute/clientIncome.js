import indexClientIncomes from "../../view/admin/clientIncomes/index";
import createClientIncomes from "../../view/admin/clientIncomes/create";
import editClientIncomes from "../../view/admin/clientIncomes/edit";
import store from "../../store/admin";

export default [
    {
        path: 'clientIncomes',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexClientIncomes',
                component: indexClientIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientIncomes read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createClientIncomes',
                component: createClientIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientIncomes create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editClientIncomes',
                component: editClientIncomes,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientIncomes edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
