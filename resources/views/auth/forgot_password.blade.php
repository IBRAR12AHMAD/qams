<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing</title>
</head>
<body>
    <div style="text-align:center;width:800px !important;margin: auto auto 30px auto;">
        <img style="text-align:center;width:273px !important;height: 100px;" src="http://obe.riphah.edu.pk/public/assets/img/riphah-logo.png" >
    </div>
    <div style="background-color:#f4f4f4;width:800px !important;margin: auto auto auto auto; height: 300px;">
        <div style="padding: 15px 30px 30px 30px;">
            <h1 style="color:#363636">Forgot your password? Don't Worry! Click continue to reset your password.</h1>
            <p style="margin-bottom: 10px;color:#363636">We received a request to reset the password associated with this email address. If you made this request, please click the link below. If you weren't trying to reset your password,
                 don't worry — You can safely ignore this email.It will take you to a web page where you can create a new password.</p>
            <a href="{{route('restpassword',['unique_id'=>$mailData['unique_id'], 'id'=>1])}}" id="continueBtn" onclick="disableButton()" style="padding: 10px 20px 10px 20px; background-color:#294e73;color: white;text-decoration: auto;">Continue</a>
        </div>
    </div>

    <script>
        function disableButton() {
            var btn = document.getElementById('continueBtn');
            btn.style.pointerEvents = 'none';
            btn.style.backgroundColor = '#cccccc';
        }
    </script>
</body>
</html>
