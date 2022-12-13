import indexProblemSellerCategory from "../../view/admin/problemSellerCategory/index";
import createProblemSellerCategory from "../../view/admin/problemSellerCategory/create";
import editProblemSellerCategory from "../../view/admin/problemSellerCategory/edit";
import store from "../../store/admin";

export default [
    {
        path: 'problemSellerCategory',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProblemSellerCategory',
                component: indexProblemSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemSellerCategory read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createProblemSellerCategory',
                component: createProblemSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemSellerCategory create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editProblemSellerCategory',
                component: editProblemSellerCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('problemSellerCategory edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
