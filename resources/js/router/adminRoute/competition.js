import indexCompetition from "../../view/admin/competition/index";
import createCompetition from "../../view/admin/competition/create";
import editCompetition from "../../view/admin/competition/edit";
import store from "../../store/admin";

export default [
    {
        path: 'competition',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexCompetition',
                component: indexCompetition,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('competition read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createCompetition',
                component: createCompetition,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('competition create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editCompetition',
                component: editCompetition,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;
competition
                    if(permission.includes('competition edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
