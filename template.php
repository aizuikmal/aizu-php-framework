<!DOCTYPE html>
<html lang="en">
    <head>
		<title>Template</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/png" href="http://<?=$_SERVER['SERVER_NAME']?>/favicon.png">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!--uikit framework-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.11/css/uikit.min.css"/>
		<link href="uikit" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.11/js/uikit.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.11/js/uikit-icons.min.js"></script>

        <!--additional fonts-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800" rel="stylesheet">

        <!--datepicker-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!--chartjs-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>


        <script>

            $(document).ready(function(){

                $('.date_picker').flatpickr({
                    altInput: true,
                    altFormat: "j F Y",
                    dateFormat: "Y-m-d",
                });


            });
            
        </script>

        <style>
            * { font-family: 'Open Sans', 'Montserrat', sans-serif; }
            a:hover { text-decoration:none; }
        </style>


	</head>
	<body>
		
		<h1>Application Title</h1>

        <hr />

        <?
        payload_show();
        ?>

	</body>
</html>