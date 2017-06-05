<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>@stack('page-title') PDF | RI JIA CONSTRUCTION</title>
    <style type="text/css">
    /*@font-face {
        font-family: 'msyh';
        font-style: normal;
        font-weight: normal;
        src: url(/fonts/msyh.ttf) format('truetype');
    }
    @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: normal;
        src: url(/fonts/OpenSans-Regular.ttf) format('truetype');
    }
    @font-face {
        font-family: 'Open Sans Bold';
        font-style: normal;
        font-weight: bold;
        src: url(/fonts/OpenSans-Bold.ttf) format('truetype');
    }
    @font-face {
        font-family: 'Open Sans ExtralBold';
        font-style: normal;
        font-weight: 800;
        src: url(/fonts/OpenSans-ExtraBold.ttf) format('truetype');
    }

    body {
        padding: 0;
        font-family: 'Open Sans', sans-serif;
    }

    .heading {
        padding-bottom: 20px;
        text-align: center;
        font-family: 'Open Sans Bold', sans-serif;
        font-size: 12px;
        font-weight: 600;
        border-bottom: 1px solid #000;
    }

        .heading .heading-line-1 {
            font-family: 'msyh';
            font-size: 20px;
            font-weight: normal;
        }

        .heading .heading-line-2 {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Open Sans ExtralBold', sans-serif;
        }

        .heading .heading-line-3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .heading .heading-footer {
            margin-top: 5px;
        }

            .heading .heading-footer span {
                width: 25px;
                display: inline-block;
            }


    .pdf-body {
        padding: 20px 0;
        font-size: 14px;
    }

    .footer {
        margin-top: 25px;
        padding-top: 10px;
        border-top: 1px solid #000;
    }*/
    @yield('style')
    </style>
</head>
<body>
    <div class="heading">
        <div class="heading-line-1">日嘉建築装修工程</div>
        <div class="heading-line-2">RI JIA CONSTRUCTION</div>
        <div class="heading-line-3">CONTRACTOR OF HOUSES, RENOVATION & ETC</div>
        <div class="address">No.38, Jalan 21, Taman Sri Jelok, 43000 Kajang, Selangor Darul Ehsan</div>
        <div class="heading-footer">
            Tel: 03-87405018
            <span></span>
            (Co.No: 00146542-H)
        </div>
    </div>
    <div class="pdf-body">
        @yield('content')
    </div>
    <div class="footer">
        @yield('footer')
    </div>
</body>
