<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
        body {
            padding-top: 50px;
        }
        .navbar-template {
            padding: 40px 15px;
        }
    </style>
</head>
<body>

    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">NavBar</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="https://github.com/fontenele/bootstrap-navbar-dropdowns" target="_blank">GitHub</a></li>
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 1 <b class="caret"></b></a>

                        <ul class="dropdown-menu">
                            <li><a href="#">Action [Menu 1.1]</a></li>
                            <li><a href="#">Another action [Menu 1.1]</a></li>
                            <li><a href="#">Something else here [Menu 1.1]</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link [Menu 1.1]</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link [Menu 1.1]</a></li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 1.1] <b class="caret"></b></a>

                                <ul class="dropdown-menu">
                                    <li><a href="#">Action [Menu 1.2]</a></li>
                                    <li>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 1.2] <b class="caret"></b></a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 1.3] <b class="caret"></b></a>

                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Action [Menu 1.4]</a></li>
                                                    <li><a href="#">Another action [Menu 1.4]</a></li>
                                                    <li><a href="#">Something else here [Menu 1.4]</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Separated link [Menu 1.4]</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">One more separated link [Menu 1.4]</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 2 <b class="caret"></b></a>

                        <ul class="dropdown-menu">
                            <li><a href="#">Action [Menu 2.1]</a></li>
                            <li><a href="#">Another action [Menu 2.1]</a></li>
                            <li><a href="#">Something else here [Menu 2.1]</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link [Menu 2.1]</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link [Menu 2.1]</a></li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 2.1] <b class="caret"></b></a>

                                <ul class="dropdown-menu">
                                    <li><a href="#">Action [Menu 2.2]</a></li>
                                    <li><a href="#">Another action [Menu 2.2]</a></li>
                                    <li><a href="#">Something else here [Menu 2.2]</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link [Menu 2.2]</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 2.2] <b class="caret"></b></a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown [Menu 2.3] <b class="caret"></b></a>

                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Action [Menu 2.4]</a></li>
                                                    <li><a href="#">Another action [Menu 2.4]</a></li>
                                                    <li><a href="#">Something else here [Menu 2.4]</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Separated link [Menu 2.4]</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">One more separated link [Menu 2.4]</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <div class="container">
        <div class="navbar-template text-center">
            <h1>Bootstrap NavBar (Updated: 15 Nov 2016)</h1>
            <p class="lead text-info">NavBar with too many childs.</p>
        </div>
    </div><!-- /.container -->
</body>
</html>