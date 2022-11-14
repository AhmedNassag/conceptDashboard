import indexPopupAds from "../../view/admin/ads/popupAds/index";
import createPopupAds from "../../view/admin/ads/popupAds/create";
import editPopupAds from "../../view/admin/ads/popupAds/edit";
import store from "../../store/admin";

export default [
    {
        path: 'popupAds',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexPopupAds',
                component: indexPopupAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('popupAds read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createPopupAds',
                component: createPopupAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('popupAds create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editPopupAds',
                component: editPopupAds,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('popupAds edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
