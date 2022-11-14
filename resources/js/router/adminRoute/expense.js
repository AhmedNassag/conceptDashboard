import indexExpense from "../../view/admin/expense/index";
import createExpense from "../../view/admin/expense/createExpense";
import editExpense from "../../view/admin/expense/editExpense";
import store from "../../store/admin";

export default [
    {
        path: 'expense',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexExpense',
                component: indexExpense,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('expense read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createExpense',
                component: createExpense,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('expense create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editExpense',
                component: editExpense,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('expense edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
