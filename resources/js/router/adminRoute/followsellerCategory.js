import indexfollowSellerCategory from "../../view/admin/followSellerCategory/index";
import createfollowSellerCategory from "../../view/admin/followSellerCategory/create";
import editfollowSellerCategory from "../../view/admin/followSellerCategory/edit";
import store from "../../store/admin";

export default [
    {
        path: 'followSellerCategory',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexfollowSellerCategory',
                component: indexfollowSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followSellerCategory read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createfollowSellerCategory',
                component: createfollowSellerCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followSellerCategory create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editfollowSellerCategory',
                component: editfollowSellerCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('followSellerCategory edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
