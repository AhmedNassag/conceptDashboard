import indexSuggestionClient from "../../view/admin/suggestionClient/index";
import showSuggestionClient from "../../view/admin/suggestionClient/show";
import store from "../../store/admin";
import editSuggestion from "../../view/admin/suggestion/edit";

export default [
    {
        path: 'SuggestionClient',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSuggestionClient',
                component: indexSuggestionClient,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SuggestionClients read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'show/:id(\\d+)',
                name: 'showSuggestionClient',
                component: showSuggestionClient,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('SuggestionClients read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            }
        ]
    },
];
