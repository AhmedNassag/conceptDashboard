import indexLeadSourse from "../../view/admin/leadSourse/index";
import createLeadSourse from "../../view/admin/leadSourse/create";
import editLeadSourse from "../../view/admin/leadSourse/edit";
import store from "../../store/admin";

export default [
    {
        path: 'leadSourse',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadSourse',
                component: indexLeadSourse,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createLeadSourse',
                component: createLeadSourse,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editLeadSourse',
                component: editLeadSourse,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('measureUnit edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
