import indexTreasury from "../../view/admin/treasury/index";
import createTreasury from "../../view/admin/treasury/createTreasury";
import editTreasury from "../../view/admin/treasury/editTreasury";
import store from "../../store/admin";

export default [
    {
        path: 'treasury',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTreasury',
                component: indexTreasury,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('treasury read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createTreasury',
                component: createTreasury,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('treasury create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editTreasury',
                component: editTreasury,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('treasury edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
