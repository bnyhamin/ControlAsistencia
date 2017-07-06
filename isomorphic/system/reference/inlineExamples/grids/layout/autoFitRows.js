isc.ListGrid.create({
    ID: "countryList",
    width:500, height:1, top:50, alternateRecordStyles:true, showAllRecords:true,
    data: countryData,
    headerProperties : {overflow:"visible"},
    fields:[
        {name:"countryCode", title:"Flag", width:50, type:"image", imageURLPrefix:"flags/16/", imageURLSuffix:".png"},
        {name:"countryName", title:"Country"},
        {name:"capital", title:"Capital"},
        {name:"continent", title:"Continent"}
    ],
    bodyOverflow: "visible",
    overflow: "visible",
    leaveScrollbarGap: false
})


isc.IButton.create({
    left:0,
    title:"Show 5",
    click:"countryList.setData(countryData.getRange(0,5))"
})

isc.IButton.create({
    left:120,
    title:"Show 10",
    click:"countryList.setData(countryData.getRange(0,10))"
})

isc.IButton.create({
    left:240,
    title:"Show all",
    click:"countryList.setData(countryData)"
})
