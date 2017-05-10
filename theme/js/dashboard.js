/**
 *	The main script file responsible for handling script execution on the dashboard page
 *
 * 	@author Gaetan Dumortier
 * 	@since 10 May 2017
*/

var base = "api/";
var key = "dev"; // api key
var userid = 1; // id of user to retrieve shifts from

// Get the shifts from the user with provided userid using an HTTP GET
$.ajax({
    url: base + "shifts/" + userid,
    type: "GET",
    dataType: "json",
    headers: {
        "Authorization": key,
    },
    success: function(data) {
        for (var i = 0; i < data.length; i++) {
            var result = data[i];
            addShift(result.id, result.date, result.startTime, result.endTime, result.isSunday);
        }
    },
    error: function(error) {
        console.log("error: " + error);
    }
});

// Append a new shift to the shifts table with the provided arguments
function addShift(id, date, startTime, endTime, isSunday) {

    var startTime = new Date(startTime * 1000);
    var endTime = new Date(endTime * 1000);
    var formattedStartTime = endTime.getHours() + ":" + "0" + endTime.getMinutes().substr(-2);
    var formattedEndTime = endTime.getHours() + ":" + "0" + endTime.getMinutes().substr(-2);
    var timeDifference = (startTime - endTime);
    var hoursWorked = timeDifference / 60 / 60;

    $("#shifts").append(
            "<tr>" +
                "<td>" + date + "</td>" +
                "<td>" + formattedStartTime + "</td>" +
                "<td>" + formattedEndTime + "</td>" +
                "<td>" + hoursWorked + "</td>" +
                "<td>" + isSunday + "</td>" +
                "<td>" +
                    "<a class='button right buttonRed' href='shift/remove/" + id + "'>Remove</a>" +
                    "<a class='button right buttonSpacingRight' href='shift/edit/" + id + "'>Edit</a></td>" +
                "</td>" +
            "</tr>"
            );
}
