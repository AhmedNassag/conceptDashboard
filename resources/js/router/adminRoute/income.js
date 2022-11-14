import indexIncome from "../../view/admin/income/index";
import createIncome from "../../view/admin/income/createIncome";
import editIncome from "../../view/admin/income/editIncome";
import store from "../../store/admin";

export default [
    {
        path: 'income',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexIncome',
                component: indexIncome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createIncome',
                component: createIncome,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editIncome',
                component: editIncome,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('income edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
