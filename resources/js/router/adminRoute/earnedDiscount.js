import indexEarnedDiscount from "../../view/admin/earnedDiscount/index";
import createEarnedDiscount from "../../view/admin/earnedDiscount/create";
import editEarnedDiscount from "../../view/admin/earnedDiscount/edit";
import store from "../../store/admin";

export default [
    {
        path: 'EarnedDiscount',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexEarnedDiscount',
                component: indexEarnedDiscount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('EarnedDiscount read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createEarnedDiscount',
                component: createEarnedDiscount,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('EarnedDiscount create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editEarnedDiscount',
                component: editEarnedDiscount,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('EarnedDiscount edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
