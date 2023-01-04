import indexTermsPrivacy from "../../view/admin/termsPrivacy/index";
import createTermsPrivacy from "../../view/admin/termsPrivacy/create";
import editTermsPrivacy from "../../view/admin/termsPrivacy/edit";
import store from "../../store/admin";

export default [
    {
        path: 'termsPrivacy',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTermsPrivacy',
                component: indexTermsPrivacy,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('termsPrivacy read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createTermsPrivacy',
                component: createTermsPrivacy,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('termsPrivacy create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editTermsPrivacy',
                component: editTermsPrivacy,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('termsPrivacy edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
