isc.ListGrid.create({
    ID: "countryList",
    width:522, height:224,
    alternateRecordStyles:true, showAllRecords:true, cellHeight:22,
    // use server-side dataSource so edits are retained across page transitions
    dataSource: countryDS,
    // display a subset of fields from the datasource
    fields:[
        {name:"countryCode", title:"Flag", width:40, type:"image", imageURLPrefix:"flags/16/", imageURLSuffix:".png"},
        {name:"countryName"},
        {name:"government"},
        {name:"continent"}
    ],
    canEdit: true,
    editEvent: "click",
    groupStartOpen:"all",
    groupByField: 'continent',
    autoFetchData: true
})

countryList.startEditing(1);

