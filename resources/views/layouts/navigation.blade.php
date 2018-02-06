<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="images/userheadphone.png" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"> {{Auth::user()->first_name}}</strong>
                             </span> <span class="text-muted text-xs block">{{Session::get('rol_name')}} <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#"> <i class="fa fa-address-card-o"></i> Perfil</a></li>
                        <li class="divider"></li>
                        <li><a href={{URL('changerol')}}><i class="fa fa-exchange"></i>  Cambiar de Rol</a></li>

                        <li class="divider"></li>
                        <li><a href="{{ url('logout')}}"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
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
