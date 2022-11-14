import indexOrderIncomes from "../../view/admin/orderIncomes/index";
import store from "../../store/admin";

export default [
    {
        path: 'orderIncomes',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexOrderIncomes',
                component: indexOrderIncomes,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('orderIncomes read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
