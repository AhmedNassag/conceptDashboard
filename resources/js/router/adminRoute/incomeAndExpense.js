import indexIncomeAndExpense from "../../view/admin/incomeAndExpense/index";
import createIncomeData from "../../view/admin/incomeAndExpense/createIncome";
import editIncomeData from "../../view/admin/incomeAndExpense/editIncome";
import createExpenseData from "../../view/admin/incomeAndExpense/createExpense";
import editExpenseData from "../../view/admin/incomeAndExpense/editExpense";
import store from "../../store/admin";

export default [
    {
        path: 'incomeAndExpense',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexIncomeAndExpense',
                component: indexIncomeAndExpense,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income&expense read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'income/accounts/create',
                name: 'createIncomeData',
                component: createIncomeData,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income&expense create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'income/accounts/edit/:id(\\d+)',
                name: 'editIncomeData',
                component: editIncomeData,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income&expense edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },

            {
                path: 'expense/accounts/create',
                name: 'createExpenseData',
                component: createExpenseData,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income&expense create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'expense/accounts/edit/:id(\\d+)',
                name: 'editExpenseData',
                component: editExpenseData,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income&expense edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
