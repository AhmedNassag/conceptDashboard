import indexProvince from "../../view/admin/province/index";
import createProvince from "../../view/admin/province/create";
import editProvince from "../../view/admin/province/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Province',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexProvince',
                component: indexProvince,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('provinces read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createProvince',
                component: createProvince,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('provinces create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editProvince',
                component: editProvince,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('provinces edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
