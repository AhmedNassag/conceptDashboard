import indexIncomeAccounts from "../../view/admin/IncomeAccounts/index";
import createIncomeAccounts from "../../view/admin/IncomeAccounts/create";
import editIncomeAccounts from "../../view/admin/IncomeAccounts/edit";
import indexSubIncomeAccounts from "../../view/admin/subIncomeAccounts/index";
import createSubIncomeAccounts from "../../view/admin/subIncomeAccounts/create";
import editSubIncomeAccounts from "../../view/admin/subIncomeAccounts/edit";
import store from "../../store/admin";

export default [
    {
        path: 'IncomeAccounts',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexIncomeAccounts',
                component: indexIncomeAccounts,
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
                name: 'createIncomeAccounts',
                component: createIncomeAccounts,
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
                name: 'editIncomeAccounts',
                component: editIncomeAccounts,
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
                path: 'subIncomeAccounts/:id(\\d+)/main/:mainId(\\d+)',
                name: 'indexSubIncomeAccounts',
                component: indexSubIncomeAccounts,
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
                path: 'subIncomeAccounts/create/:id(\\d+)/main/:mainId(\\d+)',
                name: 'createSubIncomeAccounts',
                component: createSubIncomeAccounts,
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
                path: 'subIncomeAccounts/edit/:id(\\d+)/main/:mainId(\\d+)',
                name: 'editSubIncomeAccounts',
                component: editSubIncomeAccounts,
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
