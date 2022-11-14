import indexExaminationRecord from "../../view/admin/ExaminationRecord/index";
import createExaminationRecord from "../../view/admin/ExaminationRecord/create";
import editExaminationRecord from "../../view/admin/ExaminationRecord/edit";
import store from "../../store/admin";

export default [
    {
        path: 'examinationRecord',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'indexExaminationRecord',
                component: indexExaminationRecord,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('examinationRecords read')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'create/:id(\\d+)',
                name: 'createExaminationRecord',
                component: createExaminationRecord,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('examinationRecords create')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
            {
                path: 'edit/:id(\\d+)',
                name: 'editExaminationRecord',
                component: editExaminationRecord,
                props: true,
                beforeEnter: (to, from,next) => {
                    let permission = store.state.authAdmin.permission;

                    if(permission.includes('examinationRecords edit')){
                        return next();
                    }else{
                        return next({name:'Page404'});
                    }
                }
            },
        ]
    },
];
