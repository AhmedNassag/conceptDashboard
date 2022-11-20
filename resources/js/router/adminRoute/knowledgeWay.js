import indexKnowledge from "../../view/admin/knowledgeWay/index";
import createKnowledge from "../../view/admin/knowledgeWay/create";
import editKnowledge from "../../view/admin/knowledgeWay/edit";
import store from "../../store/admin";

export default [
    {
        path: 'knowledge',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexKnowledge',
                component: indexKnowledge,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createKnowledge',
                component: createKnowledge,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editKnowledge',
                component: editKnowledge,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('department edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
