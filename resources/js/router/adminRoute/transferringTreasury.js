import indexTransferringTreasury from "../../view/admin/transferringTreasury/index";
import createTransferringTreasury from "../../view/admin/transferringTreasury/createTreasury";
import editTransferringTreasury from "../../view/admin/transferringTreasury/editTreasury";
import store from "../../store/admin";

export default [
    {
        path: 'transferringTreasury',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTransferringTreasury',
                component: indexTransferringTreasury,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('transferringTreasury read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createTransferringTreasury',
                component: createTransferringTreasury,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('transferringTreasury create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editTransferringTreasury',
                component: editTransferringTreasury,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('transferringTreasury edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
