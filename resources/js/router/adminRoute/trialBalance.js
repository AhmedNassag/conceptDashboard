import indexTrialBalance from "../../view/admin/trialBalance/index";
import store from "../../store/admin";

export default [
    {
        path: 'trialBalance',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexTrialBalance',
                component: indexTrialBalance,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('TrialBalance read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
