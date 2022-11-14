import indexFirstSliderAds from "../../view/admin/ads/firstSliderAds/index";
import createFirstSliderAds from "../../view/admin/ads/firstSliderAds/create";
import editFirstSliderAds from "../../view/admin/ads/firstSliderAds/edit";
import store from "../../store/admin";

export default [
    {
        path: 'firstSliderAds',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexFirstSliderAds',
                component: indexFirstSliderAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('firstSliderAds read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createFirstSliderAds',
                component: createFirstSliderAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('firstSliderAds create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editFirstSliderAds',
                component: editFirstSliderAds,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('firstSliderAds edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
