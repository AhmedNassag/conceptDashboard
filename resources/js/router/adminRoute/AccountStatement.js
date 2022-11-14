import indexAccountStatement from "../../view/admin/accountStatement/index";
import store from "../../store/admin";

export default [
    {
        path: 'AccountStatement',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexAccountStatement',
                component: indexAccountStatement,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('AccountStatement read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
