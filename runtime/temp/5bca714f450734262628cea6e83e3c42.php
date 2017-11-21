<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:50:"D:\phpStudy\WWW\tp5\public/../application/404.html";i:1504084392;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="admin_image/photos/user-avatar.png" type="image/png">

    <title></title>

    <!--common-->
    <link href="admin_css/style.css" rel="stylesheet">
    <link href="admin_css/style-responsive.css" rel="stylesheet">
    

    


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="admin_js/html5shiv.js"></script>
    <script src="admin_js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="<?php echo url('/admin'); ?>"><img src="admin_image/logo.png" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="index.html"><img src="" alt=""></a>
        </div>
        <!--logo and iconic logo end-->

        <div class="left-side-inner">

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li class="active"><a href="<?php echo url('/admin'); ?>"><i class="fa fa-home"></i> <span>后台首页</span></a></li>
                <li><a href="<?php echo url('staff/index'); ?>"><i class="fa fa-user"></i> <span>客服管理</span></a></li>
                <li><a href="<?php echo url('user/index'); ?>"><i class="fa fa-group"></i> <span>用户管理</span></a></li>
                <li><a href="<?php echo url('role/index'); ?>"><i class="fa fa-link"></i> <span>员工管理</span></a></li>
                <li><a href="<?php echo url('manager/index'); ?>"><i class="fa fa-sitemap"></i> <span>账户管理</span></a></li>

                <li><a href="<?php echo url('permission/index'); ?>"><i class="fa fa-cogs"></i> <span>权限管理</span></a></li>
                <li><a href="<?php echo url('login/logout'); ?>"><i class="fa fa-sign-in"></i> <span>退出登录</span></a></li>

            </ul>
            <!--sidebar nav end-->

        </div>
    </div>
    <!-- left side end-->

    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">

            <!--toggle button start-->
            <a class="toggle-btn"><i class="fa fa-bars"></i></a>
            <!--toggle button end-->

            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <img src="admin_image/photos/user-avatar.png" alt="" />
                            <?php echo $userinfo['username']; ?>
                            <span class="caret"></span>
                        </a>
                    </li>

                </ul>
            </div>
            <!--notification menu end -->

        </div>
        <!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
            <h3>
                首页
            </h3>
            <ul class="breadcrumb">
                <li>
                    <a href="#">首页</a>
                </li>
                <li class="active"> 概况 </li>
            </ul>
        </div>
        <!-- page heading end-->

        <!--body wrapper start-->
        <div class="wrapper">
            

            <div class="alert alert-danger">您没有权限访问当前页面</div>

            
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <footer>
            2014 &copy; AdminEx by ThemeBucket
        </footer>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="admin_js/jquery-1.10.2.min.js"></script>
<script src="admin_js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="admin_js/jquery-migrate-1.2.1.min.js"></script>
<script src="admin_js/bootstrap.min.js"></script>
<script src="admin_js/modernizr.min.js"></script>
<script src="admin_js/jquery.nicescroll.js"></script>
<!--common scripts for all pages-->
<script src="admin_js/scripts.js"></script>




</body>
</html>
