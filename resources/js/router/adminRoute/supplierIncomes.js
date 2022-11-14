import indexSupplierIncomes from "../../view/admin/supplierIncomes/index";
import createSupplierIncomes from "../../view/admin/supplierIncomes/create";
import editSupplierIncomes from "../../view/admin/supplierIncomes/edit";
import store from "../../store/admin";

export default [
    {
        path: 'supplierIncomes',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSupplierIncomes',
                component: indexSupplierIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SupplierIncomes read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSupplierIncomes',
                component: createSupplierIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SupplierIncomes create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSupplierIncomes',
                component: editSupplierIncomes,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SupplierIncomes edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
