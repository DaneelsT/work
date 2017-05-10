/**
 *	The main script file responsible for handling script execution on the dashboard page
 *
 * 	@author Gaetan Dumortier
 * 	@since 10 May 2017
*/

var base = "api/";
var key = "dev"; // api key

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
    // Make sure the DOM is ready before appending
    $(document).ready(function() {
        $("#shifts").append(
                "<tr>" +
                    "<td>" + date + "</td>" +
                    "<td>" + startTime + "</td>" +
                    "<td>" + endTime + "</td>" +
                    "<td>" + isSunday + "</td>" +
                    "<td>" + isSunday + "</td>" +
                    "<td>" +
                        "<a class='button right buttonRed' href='shift/remove/" + id + "'>Remove</a>" +
                        "<a class='button right buttonSpacingRight' href='shift/edit/" + id + "'>Edit</a></td>" +
                    "</td>" +
                "</tr>"
                );
    });
}
