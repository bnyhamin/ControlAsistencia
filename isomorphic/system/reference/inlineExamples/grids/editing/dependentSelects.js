isc.ListGrid.create({
    width: 500,
    height:200,
    canEdit:true,
    data:[
        {employee:"Richard Smith", division:"Marketing", department:"Community Relations"},
        {employee:"Sam Edwards", division:"Services", department:"Support"}
    ],
    fields: [
        {name:"employee", title:"Name", canEdit:false},
        {name: "division",
         title: "Division",
         editorType: "select",
         valueMap: ["Marketing", "Sales", "Manufacturing", "Services"],
         // Calling 'setValueMap()' will force the 'getEditorValueMap' method to be
         // re-evaluated for the department field
         changed:"item.grid.setValueMap('department')"
        },
        {name: "department",
         title: "Department",
         editorType: "select",
         departments: {
            Marketing: ["Advertising", "Community Relations"],
            Sales: ["Channel Sales", "Direct Sales"],
            Manufacturing: ["Design", "Development", "QA"],
            Services: ["Support", "Consulting"]
         },
         
         editorProperties:{
             addUnknownValues:false
         }
        }
    ],

    getEditorValueMap : function (field, values) {
        if (field.name != "department") return this.Super("getEditorValueMap", arguments);
        var division = values.division;
        return field.departments[division];
    }

});