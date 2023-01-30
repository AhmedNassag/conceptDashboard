import indexCallCenter from "../../view/admin/callCenter/index";
import store from "../../store/admin";

export default [
    {
        path: 'callCenter',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCallCenter',
                component: indexCallCenter,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('category read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
