import indexPointPrice from "../../view/admin/pointPrice/index";
import createPointPrice from "../../view/admin/pointPrice/create";
import editPointPrice from "../../view/admin/pointPrice/edit";
import store from "../../store/admin";

export default [
    {
        path: 'pointPrice',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPointPrice',
                component: indexPointPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointPrice read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPointPrice',
                component: createPointPrice,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointPrice create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPointPrice',
                component: editPointPrice,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointPrice edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
