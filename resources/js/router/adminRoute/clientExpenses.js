import indexClientExpenses from "../../view/admin/clientExpenses/index";
import createClientExpenses from "../../view/admin/clientExpenses/create";
import editClientExpenses from "../../view/admin/clientExpenses/edit";
import store from "../../store/admin";

export default [
    {
        path: 'clientExpenses',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexClientExpenses',
                component: indexClientExpenses,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientExpenses read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createClientExpenses',
                component: createClientExpenses,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientExpenses create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editClientExpenses',
                component: editClientExpenses,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('clientExpenses edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
