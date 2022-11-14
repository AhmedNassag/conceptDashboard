import indexDailyRestriction from "../../view/admin/dailyRestriction/index";
import createDailyRestriction from "../../view/admin/dailyRestriction/create";
import editDailyRestriction from "../../view/admin/dailyRestriction/edit";
import store from "../../store/admin";

export default [
    {
        path: 'DailyRestriction',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexDailyRestriction',
                component: indexDailyRestriction,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('DailyRestriction read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createDailyRestriction',
                component: createDailyRestriction,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('DailyRestriction create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editDailyRestriction',
                component: editDailyRestriction,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('DailyRestriction edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
