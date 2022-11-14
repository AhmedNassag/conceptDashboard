import saleReportIndex from "../../view/admin/role/index";
import store from "../../store/admin";

export default [
    {
        path: 'saleReport',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'saleReportIndex',
                component: saleReportIndex,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('role read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
