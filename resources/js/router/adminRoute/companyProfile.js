import indexCompanyProfile from "../../view/admin/companyProfile/index";
import createCompanyProfile from "../../view/admin/companyProfile/create";
import editCompanyProfile from "../../view/admin/companyProfile/edit";
import store from "../../store/admin";

export default [
    {
        path: 'companyProfile',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCompanyProfile',
                component: indexCompanyProfile,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('companyProfile read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createCompanyProfile',
                component: createCompanyProfile,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('companyProfile create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editCompanyProfile',
                component: editCompanyProfile,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if (permission.includes('companyProfile edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
