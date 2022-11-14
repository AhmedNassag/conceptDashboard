import indexSuggestion from "../../view/admin/suggestion/index";
import createSuggestion from "../../view/admin/suggestion/create";
import editSuggestion from "../../view/admin/suggestion/edit";
import store from "../../store/admin";

export default [
    {
        path: 'Suggestion',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSuggestion',
                component: indexSuggestion,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Suggestions read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSuggestion',
                component: createSuggestion,
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
                name: 'editSuggestion',
                component: editSuggestion,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('Suggestions edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
