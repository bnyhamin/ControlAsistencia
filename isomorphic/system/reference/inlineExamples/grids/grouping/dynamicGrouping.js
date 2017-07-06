isc.ListGrid.create({
    ID: "countryList",
    width:522, height:224,
    alternateRecordStyles:true, showAllRecords:true, cellHeight:22,
    dataSource: countryDS,
    // display a subset of fields from the datasource
    fields:[
        {name:"countryCode", title:"Flag", width:40, type:"image", imageURLPrefix:"flags/16/", imageURLSuffix:".png", canEdit:false},
        {name:"countryName"},
        {name:"government"},
        {name:"continent"}
    ],
    groupStartOpen:"all",
    groupByField: 'continent',
    autoFetchData: true
})

