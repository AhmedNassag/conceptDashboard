import createNotification from "../../view/admin/notification/create";
import store from "../../store/admin";

export default [
    {
        path: 'addNotificationApp',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'createNotification',
                component: createNotification,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('addNotificationApp create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
