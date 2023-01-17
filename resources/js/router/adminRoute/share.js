import indexShare from "../../view/admin/share/index";
import store from "../../store/admin";

export default [
    {
        path: 'share',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexShare',
                component: indexShare,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('share read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
