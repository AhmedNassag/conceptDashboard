import indexSubCategory from "../../view/admin/subCategory/index";
import createSubCategory from "../../view/admin/subCategory/create";
import editSubCategory from "../../view/admin/subCategory/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Subcategory',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSubCategory',
                component: indexSubCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('subCategory read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSubCategory',
                component: createSubCategory,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('subCategory create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSubCategory',
                component: editSubCategory,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('subCategory edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
