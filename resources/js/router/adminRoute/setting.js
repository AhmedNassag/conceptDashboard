import indexSetting from "../../view/admin/setting/index";
import editSetting from "../../view/admin/setting/edit";
import store from "../../store/admin";

export default [
    {
        path: 'setting',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSetting',
                component: indexSetting,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('setting read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSetting',
                component: editSetting,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('setting edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
