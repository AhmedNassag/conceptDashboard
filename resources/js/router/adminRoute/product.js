import indexProduct from "../../view/admin/product/index";
import createProduct from "../../view/admin/product/create";
import editProduct from "../../view/admin/product/edit";
import store from "../../store/admin";

export default [
    {
        path: 'product',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProduct',
                component: indexProduct,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('product read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createProduct',
                component: createProduct,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('product create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editProduct',
                component: editProduct,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('product edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
