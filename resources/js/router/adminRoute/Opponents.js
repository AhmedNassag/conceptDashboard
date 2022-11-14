import indexOpponents from "../../view/admin/Opponents/index";
import createOpponents from "../../view/admin/Opponents/create";
import editOpponents from "../../view/admin/Opponents/edit";
import indexSubOpponents from "../../view/admin/subOpponents/index";
import createSubOpponents from "../../view/admin/subOpponents/create";
import editSubOpponents from "../../view/admin/subOpponents/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Opponents',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOpponents',
                component: indexOpponents,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createOpponents',
                component: createOpponents,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editOpponents',
                component: editOpponents,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },{
                path: 'subOpponents/:id(\\d+)/main/:mainId(\\d+)',
                name: 'indexSubOpponents',
                component: indexSubOpponents,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'subOpponents/create/:id(\\d+)/main/:mainId(\\d+)',
                name: 'createSubOpponents',
                component: createSubOpponents,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'subOpponents/edit/:id(\\d+)/main/:mainId(\\d+)',
                name: 'editSubOpponents',
                component: editSubOpponents,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountsTree edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
