import indexSpareAccessor from "../../view/admin/spareAccessor/index";
import createSpareAccessor from "../../view/admin/spareAccessor/create";
import editSpareAccessor from "../../view/admin/spareAccessor/edit";
import store from "../../store/admin";

export default [
    {
        path: 'spareAccessor',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSpareAccessor',
                component: indexSpareAccessor,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSpareAccessor',
                component: createSpareAccessor,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSpareAccessor',
                component: editSpareAccessor,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('sellingMethod edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
