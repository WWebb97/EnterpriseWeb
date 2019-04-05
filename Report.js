function pageReporting(pageName){
    $.ajax({
        type: "POST",
        url: "ReportingController.php",
        data: {method:"logPageReporting", pageName: pageName},
        cache: false,
        dataType: 'JSON',
        success: function(data, status, xhttp, errorMessage) {
            console.log(pageName);
        },
        error: function(result) {
            console.log("error" + pageName);
        }
    });
}    


function instanceReporting(userId){
    var userAgent = navigator.userAgent;
    $.ajax({
        type: "POST",
        url: "ReportingController.php",
        data: {method:"logReportingInstance", browser: userAgent, userId: userId},
        cache: false,
        dataType: 'JSON',
        success: function(data, status, xhttp, errorMessage) {
            console.log(userAgent);
        },
        error: function(result) {
            console.log("error" + userAgent);
        }
    });
}
    


