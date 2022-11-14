import indexExpenseAccounts from "../../view/admin/ExpenseAccounts/index";
import createExpenseAccounts from "../../view/admin/ExpenseAccounts/create";
import editExpenseAccounts from "../../view/admin/ExpenseAccounts/edit";
import indexSubExpenseAccounts from "../../view/admin/subExpenseAccounts/index";
import createSubExpenseAccounts from "../../view/admin/subExpenseAccounts/create";
import editSubExpenseAccounts from "../../view/admin/subExpenseAccounts/edit";
import store from "../../store/admin";

export default [
    {
        path: 'ExpenseAccounts',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexExpenseAccounts',
                component: indexExpenseAccounts,
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
                name: 'createExpenseAccounts',
                component: createExpenseAccounts,
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
                name: 'editExpenseAccounts',
                component: editExpenseAccounts,
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
                path: 'subExpenseAccounts/:id(\\d+)/main/:mainId(\\d+)',
                name: 'indexSubExpenseAccounts',
                component: indexSubExpenseAccounts,
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
                path: 'subExpenseAccounts/create/:id(\\d+)/main/:mainId(\\d+)',
                name: 'createSubExpenseAccounts',
                component: createSubExpenseAccounts,
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
                path: 'subExpenseAccounts/edit/:id(\\d+)/main/:mainId(\\d+)',
                name: 'editSubExpenseAccounts',
                component: editSubExpenseAccounts,
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
