import indexFinancialCenter from "../../view/admin/financialCenter/index";
import store from "../../store/admin";

export default [
    {
        path: 'financialCenter',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexFinancialCenter',
                component: indexFinancialCenter,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('FinancialCenter read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
