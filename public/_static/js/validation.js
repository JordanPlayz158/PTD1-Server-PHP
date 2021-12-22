//Listen when a button, with a class of "myButton", is clicked
//You can use any jQuery/JavaScript event that you'd like to trigger the call
function validate() {
    //Send the AJAX call to the server
    $.ajax({
        //The URL to process the request
        'url' : 'reset_password.php',
        //The type of request, also known as the "method" in HTML forms
        //Can be 'GET' or 'POST'
        'type' : 'POST',
        //Any post-data/get-data parameters
        //This is optional
        'data' : {
            'email' : $('#email').val(),
        },
        //The response from the server
        'success' : function(data) {
            //You can use any jQuery/JavaScript here!!!
            console.log(data);

            switch(data) {
                case 'success':
                    alert('Email sent successfully!');
                    break;
                case '0':
                    alert('Unknown error occurred while trying to send email');
                    break;
                case '1':
                    alert('Email field cannot be empty!')
                    break;
                case '2':
                    alert('Email must contain "@"')
                    break;
                case '3':
                    alert('Domain must have an mx record associated with it')
                    break;
                case '8':
                    alert('Haha! Nice try!');
                    break;
            }
        }
    });
}