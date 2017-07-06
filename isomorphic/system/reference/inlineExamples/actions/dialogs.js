isc.Button.create({
    title: "Confirm",
    click : function () {
        isc.confirm("Proceed with Operation get AJAX?",
                    "answer.setContents(value ? 'OK' : 'Cancel')");
    }
});

isc.Button.create({
    title: "Ask",
    left: 150,
    click : function () {
        isc.ask("Are you going to stop writing great code?",
                "answer.setContents(value ? 'Yes' : 'No')");
    }
});

isc.Label.create({
    ID: "answer",
    contents: "Your answer here...",
    top: 50,
    width: 300
});
