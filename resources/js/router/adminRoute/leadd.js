import indexLeadd from "../../view/admin/leadd/index";
import createLeadd from "../../view/admin/leadd/create";
import editLeadd from "../../view/admin/leadd/edit";
// import showLeadd from "../../view/admin/leadd/show";
import store from "../../store/admin";

export default [
    {
        path: 'leadd',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexLeadd',
                component: indexLeadd,
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
                name: 'createLeadd',
                component: createLeadd,
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
                name: 'editLeadd',
                component: editLeadd,
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
            // {
            //     path: 'show/:id(\\d+)',
            //     name: 'showLeadd',
            //     component: showLeadd,
            //     props: true,
            //     beforeEnter: (to, from,next) => {
            //         let permission = store.state.authAdmin.permission;
            //
            //         if(permission.includes('category edit')){
            //             return next();
            //         }else{
            //             return next({name:'Page404'});
            //         }
            //     }
            // },
        ]
    },
];
