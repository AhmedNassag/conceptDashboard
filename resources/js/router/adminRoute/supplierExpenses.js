import indexSupplierExpenses from "../../view/admin/supplierExpenses/index";
import createSupplierExpenses from "../../view/admin/supplierExpenses/create";
import editSupplierExpenses from "../../view/admin/supplierExpenses/edit";
import store from "../../store/admin";

export default [
    {
        path: 'supplierExpenses',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSupplierExpenses',
                component: indexSupplierExpenses,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('supplierExpenses read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSupplierExpenses',
                component: createSupplierExpenses,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('supplierExpenses create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSupplierExpenses',
                component: editSupplierExpenses,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('supplierExpenses edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
