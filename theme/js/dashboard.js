/**
 *	The main script file responsible for handling script execution on the dashboard page
 *
 * 	@author Gaetan Dumortier
 * 	@since 10 May 2017
*/

var base = "api/";
var key = "dev"; // api key

$(document).ready(function() {

    // try and fetch shifts from user with id 1 when testButton has been clicked.
    $("#testButton").click(function() {
        $.ajax({
            url: base + "api/shifts/1",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": key,
            },
            success: function(data) {
                console.log(data.date);
                console.log(data.startTime);
            },
            error: function(error) {
                console.log("error: " + error);
            }
        });
    });

});
