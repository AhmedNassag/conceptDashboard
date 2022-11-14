import indexDiscount from "../../view/admin/discount&Offer/index";
import createDiscount from "../../view/admin/discount&Offer/create";
import editDiscount from "../../view/admin/discount&Offer/edit";
import store from "../../store/admin";

export default [
    {
        path: 'discountOffer',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexDiscountOffer',
                component: indexDiscount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('discount read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createDiscountOffer',
                component: createDiscount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('discount create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editDiscountOffer',
                component: editDiscount,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('discount edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
