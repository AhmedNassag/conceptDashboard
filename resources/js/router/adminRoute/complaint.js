import indexComplaint from "../../view/admin/complaint/index";
import createComplaint from "../../view/admin/complaint/create";
import editComplaint from "../../view/admin/complaint/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Complaint',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexComplaint',
                component: indexComplaint,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Complaints read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createComplaint',
                component: createComplaint,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Suggestions create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editComplaint',
                component: editComplaint,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Complaints edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
