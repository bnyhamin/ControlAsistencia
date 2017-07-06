isc.TreeGrid.create({
    ID: "employeeTree",
    width: 500,
    height: 400,
    dataSource: "employees",
    nodeIcon:"icons/16/person.png",
    folderIcon:"icons/16/person.png",
    showOpenIcons:false,
    showDropIcons:false,
    closedIconSuffix:"",
    fields: [
        {name: "Name"},
        {name: "Job"}
    ],
    dataProperties: {openProperty: "isOpen"},
    initialData: [
        {EmployeeId:"4", ReportsTo:"1", Name:"Charles Madigen", Job:"Chief Operating Officer", 
         isOpen: true},
        {EmployeeId:"192", ReportsTo:"4", Name:"Ralph Brogan", Job:"Mgr Software Client Supp"},
        {EmployeeId:"191", ReportsTo:"4", Name:"Tammy Plant", Job:"Mgr Cap Rptg Dist"},
        {EmployeeId:"190", ReportsTo:"4", Name:"Carol Finley", Job:"Mgr Fin Rpts Budgets"},
        {EmployeeId:"189", ReportsTo:"4", Name:"Gene Porter", Job:"Mgr Tech Plng IntIS T"},
        {EmployeeId:"188", ReportsTo:"4", Name:"Rogine Leger", Job:"Mgr Syst P P"}, 
        {EmployeeId:"187", ReportsTo:"4", Name:"Abigail Lippman", Job:"Mgr Proj Del"}, 
        {EmployeeId:"186", ReportsTo:"4", Name:"John Garrison", Job:"Mgr Site Services"}, 
        {EmployeeId:"185", ReportsTo:"4", Name:"Rui Shu", Job:"Mgr Proj Del", Phone:"x29930"}, 
        {EmployeeId:"184", ReportsTo:"4", Name:"Kirill Amirov", Job:"Mgr Tech Plng IntIS T"}, 
        {EmployeeId:"183", ReportsTo:"4", Name:"Joan Little", Job:"Mgr Ther Gen", Phone:"x18451"}, 
        {EmployeeId:"183", ReportsTo:"4", Name:"Joan Little", Job:"Mgr Ther Gen", Phone:"x18451"}, 
        {EmployeeId:"182", ReportsTo:"4", Name:"Tamara Kane", Job:"Mgr Site Services"}
    ]
});
