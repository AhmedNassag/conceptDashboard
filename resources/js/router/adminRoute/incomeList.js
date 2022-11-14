import indexIncomeList from "../../view/admin/incomeList/index";
import store from "../../store/admin";

export default [
    {
        path: 'incomeList',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexIncomeList',
                component: indexIncomeList,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('IncomeList read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
