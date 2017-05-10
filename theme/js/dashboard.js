/**
 *	The main script file responsible for handling script execution on the dashboard page
 *
 * 	@author Gaetan Dumortier
 * 	@since 10 May 2017
*/

var base = "api/";
var key = "dev"; // api key

function addShift(date, startTime, endTime, isSunday) {
    // TODO: implement.

    $("body")
        .append(date)
        .append(startTime);
}

$(document).ready(function() {

    // try and fetch shifts from user with id 1 when testButton has been clicked.
    $("#testButton").click(function() {
        $.ajax({
            url: base + "shifts/1",
            type: "GET",
            dataType: "json",
            headers: {
                "Authorization": key,
            },
            success: function(data) {
                for (var i = 0; i < data.length; i++) {
                    var result = data[i];

                    addShift(result.date, result.startTime, result.endTime, result.isSunday);
                }
            },
            error: function(error) {
                console.log("error: " + error);
            }
        });
    });

});
