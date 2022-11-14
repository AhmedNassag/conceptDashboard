import treasuriesExpense from "../../view/admin/treasuriesExpense/index";

export default [
    {
        path: 'treasuriesExpense',
        component:  {
            template:'<router-view />',
        },
        children:[
            {
                path: '',
                name: 'treasuriesExpense',
                component: treasuriesExpense,
            }
        ]
    },
];
