<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajax</title>
</head>
<body>
    <button onclick="clients()" class="btn">Run</button>
    <div id="showMsg"></div>
    <div id="showMsg1"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        function clients(){
            name = "Ajayi";
            //or jQuery.ajax({});
            $.ajax({
                //async: false,  //by default true
                //processData: true, //jQuery automatically converts data (if it's an object) into a URL-encoded query string (e.g., key1=value1&key2=value2).
                method: "POST",
                data: {name: name},     //sending "data" along with the request to server
                dataType: "json",      //type of data we are expecting back from server e.g html, json, xml, text etc
                url: "extract_clients.php",
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                beforeSend: function(){
                    $(".btn").attr("disabled", true); //disable button while .prop() can also be used but .attr() is preferred for older versions of jQuery
                    $("#showMsg").html("Processing Request, please wait...");
                },
                success: function(response){
                    $("#showMsg").html(response.Job); //displaying the data in div
                },
                error: function() {
                    alert("Error occurred");
                },
                complete: function(){
                    $(".btn").attr("disabled", false);
                }

            });
            $("#showMsg1").html("Asynchronous Request Sent");
        }
    </script>
</body>
</html>