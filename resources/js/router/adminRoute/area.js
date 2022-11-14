import indexArea from "../../view/admin/areas/index";
import createArea from "../../view/admin/areas/create";
import editArea from "../../view/admin/areas/edit";
import store from "../../store/admin";

export default [
    {
        path: 'area',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexArea',
                component: indexArea,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('areas read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createArea',
                component: createArea,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('areas create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editArea',
                component: editArea,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('areas edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
