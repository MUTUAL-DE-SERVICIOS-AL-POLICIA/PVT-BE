<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David Williams</strong>
                             </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#">Perfil</a></li>
                        <li><a href="#"></a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('logout')}}">Cerrar Sesión</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
           
            <li class="{{ isActiveRoute('affiliate.index') }}">
                <a href="{{ url('/affiliate') }}"><i class="fa fa-user"></i> <span class="nav-label">Afiliados</span></a>
            </li>
        </ul>

    </div>
</nav>
