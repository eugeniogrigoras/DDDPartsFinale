</head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper">
                <a style="display:block; margin: 0 13px;" class="button-collapse" href="#" data-activates="slide-out"><i class="material-icons">menu</i></a>
                <a href="#!" style="font-weight:300; font-size:24px;" class="brand-logo">DDDParts</a>
                <ul class="right hide-on-med-and-down" style="height:inherit!important; font-weight:300">
                    <?php if (isset($_SESSION["ID"])): ?>
                    <li>
                        <a data-alignment="right" data-constrainwidth="false" data-beloworigin="true" class="nav dropdown-button waves-effect" data-activates="notifications">
                            <i style="margin:0!important" class="material-icons">notifications</i>
                            <span class="new badge deep-orange accent-2">4</span>
                        </a>
                        <ul style="font-weight:400!important" id='notifications' class='dropdown-content'>
                            <li><a href="">NOTIFICATIONS<span style="margin-left:15px" class="new badge deep-orange accent-2">4</span></a></li>
                            <li class="divider"></li>
                            <li><a href="#!">one</a></li>
                            <li><a href="#!">two</a></li>
                        </ul>
                    </li>
                    <li style="margin-right:13px">
                        <a data-beloworigin="true" class='truncate dropdown-button waves-effect' data-activates='profile-option-nav'>
                            <i class="material-icons left">account_circle</i>
                            <i style="margin:0!important" class="material-icons right">arrow_drop_down</i>
                            <?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?>
                        </a>
                        <ul style="font-weight:400!important" id='profile-option-nav' class='dropdown-content'>
                            <li><a class="waves-effect" href="/account"><i class="material-icons">face</i>Profile</a></li>
                            <li><a class="waves-effect" href="/settings"><i class="material-icons">settings</i>Settings</a></li>
                            <li class="divider"></li>
                            <form action="/form.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" value="logout" name="getpage">
                                <li><a class="waves-effect" onclick="document.getElementById('logout').click();"><i class="material-icons">keyboard_tab</i>Logout</a></li>
                                <button type="submit" id="logout" name="submit" style="display:none"></button>
                            </form>
                        </ul>
                    </li>
                    <?php else: ?>
                        <li style="margin-right: 13px"><a class="waves-effect" href="/login" style="text-align: center;"><i class="material-icons">account_circle</i>Login</a><li>
                    <?php endif; ?>
                </ul> 
            </div>
            <ul id="slide-out" class="side-nav">
                <?php if (isset($_SESSION["ID"])): ?>
                    <li id="account" class="row">
                        <div class="col s3 profile-image">
                            <img src="<?php echo requestPath()."/profile.jpg";?>" alt="" class="z-depth-1 circle">
                        </div>
                        <div class="col s9 profile-menu">
                            <a data-beloworigin="true" class='truncate dropdown-button waves-effect' data-activates='profile-option'>
                                <i style="margin:0!important; margin-right:-8px!important" class="material-icons right">arrow_drop_down</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?>
                            </a>

                            <ul id='profile-option' class='dropdown-content'>
                                <li><a class="waves-effect" href="/account"><i class="material-icons">face</i>Profile</a></li>
                                <li><a class="waves-effect" href="/settings"><i class="material-icons">settings</i>Settings</a></li>
                                <li class="divider"></li>
                                <form action="/form.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="logout" name="getpage">
                                    <li><a class="waves-effect" onclick="document.getElementById('logout').click();"><i class="material-icons">keyboard_tab</i>Logout</a></li>
                                    <button type="submit" id="logout" name="submit" style="display:none"></button>
                                </form>
                            </ul>
                        </div>  
                    </li>

                    <li class="search hoverable" style="margin:15px;">
                        <div class="input-field" style="height:45px!important; box-shadow:0 2px 2px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);">
                            <input style="color:#444; height:45px!important; width:180px; margin:0; padding:0 15px;" type="text" placeholder="Search...">
                        </div>
                    </li>
                <?php else: ?>
                    <li class="search hoverable" style="margin:15px;">
                        <div class="input-field" style="height:45px!important; box-shadow:0 2px 2px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);">
                            <input style="color:#444; height:45px!important; width:180px; margin:0; padding:0 15px;" type="text" placeholder="Search...">
                        </div>
                    </li>
                    <li><a class="waves-effect" href="/login"><i class="material-icons">account_circle</i>Account</a></li>
                <?php endif; ?>
                
                <li><a class="waves-effect" href="/"><i class="material-icons">dashboard</i>Dashboard</a></li>
                <li><a class="waves-effect" href="#!"><i class="material-icons">explore</i>Explore</a></li>
                <li><a class="waves-effect" href="#!"><i class="material-icons">book</i>Learn</a></li>
                <li><a class="waves-effect" href="/create"><i class="material-icons">create</i>Create</a></li>
            </ul>
        </nav>
        </div>