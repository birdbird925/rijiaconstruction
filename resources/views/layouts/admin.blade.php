<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Admin | RI JIA CONSTRUCTION</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="/css/admin/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="/css/admin/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="/css/admin/style.css" rel="stylesheet" />
    <link href="/css/admin/sweetalert.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="/css/admin/material.css" /> --}}
	{{-- <link rel="stylesheet" href="/css/admin/material-datatable.css" /> --}}
    <link href="/css/admin/app.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/admin/sweetalert.min.js"></script>
    <script src="/js/admin/jquery.datatable.min.js"></script>
     <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="admin-wrapper">
    <div class="navbar navbar-inverse set-radius-zero">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
					RI JIA CONSTRUCTION
                </a>
            </div>
        </div>
    </div>
    <!-- LOGO HEADER END-->
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a class="@stack('quotation-menu')" href="/admin/quotation">Quotation</a></li>
                            <li><a class="@stack('invoice-menu')" href="/admin/invoice">Invoice</a></li>
                            <li><a class="@stack('demo')" href="/admin/demo">Demo</a></li>
                            <li><a class="@stack('account-menu')" href="/admin/account">Account</a></li>
                            <li><a href="/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">
                        @yield('page-direction')
                        <div class="pull-right">
                            @yield('page-button')
                        </div>
                    </h4>
                </div>
            </div>
            @if($errors->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if(Session::has('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            {!! Session::get('success') !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
				@yield('content')
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    &copy; 2017 RI JIA CONSTRUCTION <span class="create_by">DEVELOP BY <a href="mailto:xiangwen94@gmail.com">LOOI</a></span>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    {{-- <script src="/js/admin/jquery-1.11.1.js"></script> --}}
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="/js/admin/bootstrap.js"></script>
    <script src="/js/admin/admin.js"></script>
</body>
</html>
