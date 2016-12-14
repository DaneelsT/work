$("#fetch").click(function() {
    
    $.ajax({
        url: "http://joerihermans.com/~gaetan/workbeta/api/user/1",
        type: "GET",
        dataType: "json",
        headers: {
            "Authorization": "dev",
        },
        success: function(data) {
            $("#result").html(data);
            console.log(data);
            console.log("\n username " + data.username);
        },
        error: function(error) {
            console.log("ERROR: " + error);
        }
    });
    
});