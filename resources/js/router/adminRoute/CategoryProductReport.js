import productReport from "../../view/admin/categoryProductReport/product";
import categoryReport from "../../view/admin/categoryProductReport/category";
import store from "../../store/admin";

export default [
    {
        path: '/productReport',
        name: 'productReport',
        component: productReport,
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
        path: '/categoryReport',
        name: 'categoryReport',
        component: categoryReport,
        beforeEnter: (to, from,next) => {
            let permission = store.state.authAdmin.permission;

            if(permission.includes('category create')){
                return next();
            }else{
                return next({name:'Page404'});
            }
        }
    }
];
