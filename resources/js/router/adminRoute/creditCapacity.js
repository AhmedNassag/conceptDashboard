import indexCreditCapacity from "../../view/admin/creditCapacity/index";
import editCreditCapacity from "../../view/admin/creditCapacity/edit";
import store from "../../store/admin";

export default [
    {
        path: 'creditCapacity',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCreditCapacity',
                component: indexCreditCapacity,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('CreditCapacity read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editCreditCapacity',
                component: editCreditCapacity,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('CreditCapacity edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
