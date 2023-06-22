<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dinas Lingkungan Hidup</title>
    <meta name="description" content="Spirit8 is a Digital agency one page template built based on bootstrap framework. This template is design by Robert Berki and coded by Jenn Pereira. It is simple, mobile responsive, perfect for portfolio and agency websites. Get this for free exclusively at ThemeForces.com">
    <meta name="keywords" content="bootstrap theme, portfolio template, digital agency, onepage, mobile responsive, spirit8, free website, free theme, themeforces themes, themeforces wordpress themes, themeforces bootstrap theme">
    <meta name="author" content="ThemeForces.com">
    
    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="{{baseURL}}/assets/template/img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{baseURL}}/assets/template/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{baseURL}}/assets/template/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{baseURL}}/assets/template/img/apple-touch-icon-114x114.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css"  href="{{baseURL}}/assets/template/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{baseURL}}/assets/template/fonts/font-awesome/css/font-awesome.css">

    <!-- Slider
    ================================================== -->
    <link href="{{baseURL}}/assets/template/css/owl.carousel.css" rel="stylesheet" media="screen">
    <link href="{{baseURL}}/assets/template/css/owl.theme.css" rel="stylesheet" media="screen">

    <!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="{{baseURL}}/assets/template/css/style.css">
    <link rel="stylesheet" type="text/css" href="{{baseURL}}/assets/template/css/responsive.css">

    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,700,300,600,800,400' rel='stylesheet' type='text/css'>

    <style>
        #line-custom{
            height: 40px;
            border-bottom: 3px solid #e5e5e5;
        }

        .grid-center{
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            grid-column: 2/3;
        }

        #p-20{
            padding: 20px 0 0;
        }

        #p-80{
            padding: 80px 0;
        }

        .title-content{
            color: #28a745;
        }

        #pengajuan{
            background-color: #1f6a3f; 
            color: #ffffff;
        }
    </style>

    <script type="text/javascript" src="{{baseURL}}/assets/template/js/modernizr.custom.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- Navigation
    ==========================================-->
    {% include "users/layout/nav.php" %}

    <!-- Content
    ==========================================-->

    <!-- Home Page
    ==========================================-->
    {% include "users/portal/home.php" %}

    <!-- Wastewater Page
    ==========================================-->
    {% include "users/portal/wastewater.php" %}

    <!-- Emission Page
    ==========================================-->
    {% include "users/portal/emission.php" %}

    <!-- Waste B3 Page
    ==========================================-->
    {% include "users/portal/waste_b3.php" %}

    <!-- Waste B3 Page
    ==========================================-->
    {% include "users/portal/contact.php" %}

    <!-- Footer
    ==========================================-->
    {% include "users/layout/footer.php" %}


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{baseURL}}/assets/template/js/jquery.1.11.1.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="{{baseURL}}/assets/template/js/bootstrap.js"></script>
    <script type="text/javascript" src="{{baseURL}}/assets/template/js/SmoothScroll.js"></script>
    <script type="text/javascript" src="{{baseURL}}/assets/template/js/jquery.isotope.js"></script>

    <script src="{{baseURL}}/assets/template/js/owl.carousel.js"></script>

    <!-- Javascripts
    ================================================== -->
    <script type="text/javascript" src="{{baseURL}}/assets/template/js/main.js"></script>

  </body>
</html>