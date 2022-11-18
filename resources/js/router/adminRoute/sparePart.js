import indexSparePart from "../../view/admin/sparePart/index";
import createSparePart from "../../view/admin/sparePart/create";
import editSparePart from "../../view/admin/sparePart/edit";
import store from "../../store/admin";

export default [
    {
        path: 'sparePart',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSparePart',
                component: indexSparePart,
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
                name: 'createSparePart',
                component: createSparePart,
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
                name: 'editSparePart',
                component: editSparePart,
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
