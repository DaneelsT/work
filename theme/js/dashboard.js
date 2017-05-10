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

// Format a date from unix timestamp to readable hh:mm format
function formatDate(timestamp) {
    var date = new Date(timestamp * 1000);
    var hours = date.getUTCHours();
    var minutes = "0" + date.getMinutes();

    return hours + ':' + minutes.substr(-2);
}

// Append a new shift to the shifts table with the provided arguments
function addShift(id, date, startTime, endTime, isSunday) {
    var timeDifference = (endTime - startTime);
    var hoursWorked = timeDifference / 60 / 60;

    $("#shifts").append(
            "<tr>" +
                "<td>" + date + "</td>" +
                "<td>" + formatDate(startTime) + "</td>" +
                "<td>" + formatDate(endTime) + "</td>" +
                "<td>" + hoursWorked + "</td>" +
                "<td>" + isSunday + "</td>" +
                "<td>" +
                    "<a class='button right buttonRed' href='shift/remove/" + id + "'>Remove</a>" +
                    "<a class='button right buttonSpacingRight' href='shift/edit/" + id + "'>Edit</a></td>" +
                "</td>" +
            "</tr>"
            );
}
