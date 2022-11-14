import indexSecondSliderAds from "../../view/admin/ads/secondSliderAds/index";
import createSecondSliderAds from "../../view/admin/ads/secondSliderAds/create";
import editSecondSliderAds from "../../view/admin/ads/secondSliderAds/edit";
import store from "../../store/admin";

export default [
    {
        path: 'secondSliderAds',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexSecondSliderAds',
                component: indexSecondSliderAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('secondSliderAds read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create',
                name: 'createSecondSliderAds',
                component: createSecondSliderAds,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('secondSliderAds create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editSecondSliderAds',
                component: editSecondSliderAds,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('secondSliderAds edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
