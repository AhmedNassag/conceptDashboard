import indexPointWelcome from "../../view/admin/pointWelcome/index";
import createPointWelcome from "../../view/admin/pointWelcome/create";
import editPointWelcome from "../../view/admin/pointWelcome/edit";
import store from "../../store/admin";

export default [
    {
        path: 'pointWelcome',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPointWelcome',
                component: indexPointWelcome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointWelcome read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPointWelcome',
                component: createPointWelcome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointWelcome create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPointWelcome',
                component: editPointWelcome,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('pointWelcome edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
