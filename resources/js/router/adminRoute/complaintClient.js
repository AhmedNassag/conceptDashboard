import indexComplaintClient from "../../view/admin/complaintClient/index";
import showComplaintClient from "../../view/admin/complaintClient/show";
import store from "../../store/admin";
import editComplaint from "../../view/admin/complaint/edit";

export default [
    {
        path: 'ComplaintClient',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexComplaintClient',
                component: indexComplaintClient,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('ComplaintClients read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showComplaintClient',
                component: showComplaintClient,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('ComplaintClients read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
