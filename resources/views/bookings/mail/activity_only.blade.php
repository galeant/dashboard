<!DOCTYPE html>
<html>
<head>
<title>A Responsive Email Template</title>
<!--

    An email present from your friends at Litmus (@litmusapp)

    Email is surprisingly hard. While this has been thoroughly tested, your mileage may vary.
    It's highly recommended that you test using a service like Litmus (http://litmus.com) and your own devices.

    Enjoy!

 -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/solid.css" integrity="sha384-wnAC7ln+XN0UKdcPvJvtqIH3jOjs9pnKnq9qX68ImXvOGz2JuFoEiCjT8jyZQX2z" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css" integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">

<style type="text/css">
    /* CLIENT-SPECIFIC STYLES */
    body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
    table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
    img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
    /* .footer{
        width: 100%;
        position:fixed;
        bottom: 0px;
        right: 0px;
        z-index: 1000;
    } */


    /* RESET STYLES */
    img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
    table{border-collapse: collapse !important;}
    body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}




    /* Extra style */
    .row::after {
        content: "";
        clear: both;
        display: table;
    }
    .bg-orange{
        background-color: #E17306;
    }
    .bg-transparent{
        background-color: transparent;
    }
    .btn-socmed{
        border: none;
        outline: none;
        cursor: pointer;
    }
    .btn-socmed{

    }
    .hr-header{
        border-top: 8px solid #E17306;
        width: 100%
    }


    /* iOS BLUE LINKS */
    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }


    /* BIGGER THAN MOBILE */
    @media screen and (min-width: 525px){
        .tab-none{
            display: none;
        }
    }

    /* MOBILE STYLES */
    @media screen and (max-width: 525px) {

        /* ALLOWS FOR FLUID TABLES */
        .wrapper {
          width: 100% !important;
        	max-width: 100% !important;
        }

        /* ADJUSTS LAYOUT OF LOGO IMAGE */
        .logo img {
          margin: 0 auto !important;
        }

        /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
        .mobile-hide {
          display: none !important;
        }

        .img-max {
          max-width: 100% !important;
          width: 100% !important;
          height: auto !important;
        }

        /* FULL-WIDTH TABLES */
        .responsive-table {
          width: 100% !important;
        }

        .width-80-mobile{
            margin-left: 10%;
            margin-right: 10%;
        }

        .width-90-mobile{
            margin-left: 5%;
            margin-right: 5%;
        }
    

        /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
        .padding {
          padding: 10px 5% 15px 5% !important;
        }

        .padding-meta {
          padding: 30px 5% 0px 5% !important;
          text-align: center;
        }

        .no-padding {
          padding: 0 !important;
        }

        .section-padding {
          padding: 0px 15px 50px 15px !important;
        }

        /* ADJUST BUTTONS ON MOBILE */
        .mobile-button-container {
            margin: 0 auto;
            width: 100% !important;
        }

        .mobile-button {
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
        }
    }

    /* ANDROID CENTER FIX */
    div[style*="margin: 16px 0;"] { margin: 0 !important; }
</style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">

<!-- HIDDEN PREHEADER TEXT -->
    <!-- <hr class="hr-header" /> -->

<div class="bg-orange" style="width: 100%; height: 15px;">
    
</div>

<!-- HEADER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="white" align="center">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="wrapper">
                <tr>
                    <td align="center" valign="top" style="padding: 15px 0;" class="logo">
                        <a href="#" target="_blank">
                            <img alt="Logo" src="{{asset('images/logo.png')}}" width="100" height="100" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px; padding-bottom: 20px;" border="0">
                        </a>
                        <hr class="width-80-mobile" />
                    </td>
                </tr>
            </table>
            
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 0px 15px 70px 15px;" class="section-padding">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td>
                        <!-- HERO IMAGE -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              	
                            </tr>
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333;" class="padding"><b>Your Next Trip Awaits You!</b></td>
                                        </tr>
                                        <tr>
                                            <td align="justify" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding">
                                                <p>Dear, {{$supplier['fullname']}}. <span>your booking with Booking Number 1011101010111 has been succesfully confirmed, and now you are all set and ready to get on your next trip.</span></p>
                                                <p>Please find itinerary handy to confirm your booking receipt and your trip itinerary details.</p>
                                            </td>
                                        </tr>
                                        
                                        <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0"></table>
                                            <tr style="border: 1px solid lightgray; border-radius: 3px;">
                                                <td align="left "style="padding: 15px;">
                                                    <i class="fas fa-exclamation-circle" style="color: lightgray;"></i>
                                                </td>
                                                <td align="justify" class="padding">
                                                    <span>
                                                        With Pigijo, all your booking details are being kept in one place. Simply keep your trip itinerary handy to confirm your booking at the location.
                                                    </span>
                                                </td>
                                            </tr>
                                        </table> -->
                                    </table>
                                    <table>
                                        <tr style="border: 2px solid lightgray; border-radius: 3px;">
                                            <td align="left "style="padding-left: 15px;">
                                                <i class="fas fa-exclamation-circle" style="color: lightgray;"></i>
                                            </td>
                                            <td align="justify" style="padding: 10px;">
                                                <small style="color:gray;">
                                                    With Pigijo, all your booking details are being kept in one place. Simply keep your trip itinerary handy to confirm your booking at the location.
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <hr class="" style="margin-top: 40px;"/>
                                            <td align="left" class="mobile-hide">
                                                <img src="./assets/maskot.png" alt="" width="125" height="125">
                                            </td>
                                            <td align="justify" style="padding-left: 20px;" class="padding">
                                                <!-- <img src="./assets/logo.png" alt=""> -->
                                                <p>Need Help?</p>
                                                <small style="color: gray;">If you are having any issue with your account, please don't be hesitate to contact us via <span><a href="mailto:info@pigijo.com?Subject=Booking%20Receip" style="color: #E17306;text-decoration: none;">info@pigijo.com</a></span>.</small>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr class="footer">
        <td bgcolor="#37383" align="center" style="padding: 25px 0px;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <!-- UNSUBSCRIBE COPY -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 500px;" class="responsive-table mobile-hide">
                <tr>
                    <td align="left" style="padding-left: 20px;">
                        <img src="{{asset('images/logo-white.png')}}" alt="logo white">
                    </td>
                    <td align="right" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666; padding-right: 10px;">
                        <button class="btn-socmed bg-transparent">
                            <a href="https://www.facebook.com/PIGIJOID/" target="_blank"><img src="{{asset('images/facebook.png')}}" alt="fb-button"></a> 
                        </button>
                        <button class="btn-socmed bg-transparent">
                            <a href="https://twitter.com/PigijoTweet" target="_blank"><img src="{{asset('images/twitter.png')}}" alt="twitter-button"></a> 
                        </button>
                        <button class="btn-socmed bg-transparent">
                            <a href="https://www.instagram.com/pigijoo/    " target="_blank"><img src="{{asset('images/instagram.png')}}" alt="instagram-button"></a> 
                        </button>
                    </td>  
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding ="0" align="center" style="max-width: 500px; margin: auto;" class="responsive-table tab-none">
                <tr>
                    <td cellpadding:"30" align="center" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666; margin-left: 30px;">
                        <button class="btn-socmed bg-transparent">
                            <a href="https://www.facebook.com/PIGIJOID/" target="_blank"><img src="{{asset('images/facebook.png')}}" alt="fb-button"></a> 
                        </button>
                        <button class="btn-socmed bg-transparent">
                            <a href="https://twitter.com/PigijoTweet" target="_blank"><img src="{{asset('images/twitter.png')}}" alt="twitter-button"></a> 
                        </button>
                        <button class="btn-socmed bg-transparent">
                            <a href="https://www.instagram.com/pigijoo/    " target="_blank"><img src="{{asset('images/instagram.png')}}" alt="instagram-button"></a> 
                        </button>
                    </td> 
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding ="0" align="" style="max-width: 500px;" class="responsive-table tab-none">
                <tr>
                    <td align="center" style="">
                        <img src="{{asset('images/logo-white.png')}}" alt="logo white">
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>
</body>
</html>
