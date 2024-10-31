<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu bg-info-subtle">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{url('index')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="20">
            </span>
        </a>

        <a href="{{url('index')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">@lang('translation.Menu')</li>

                <li>
                    <a href="{{url('index')}}">
                        <i class="uil-home-alt"></i><span class="badge rounded-pill bg-primary float-end"></span>
                        <span>@lang('translation.Dashboard')</span>
                    </a>
                </li>


                <li class="menu-title">@lang('translation.Apps')</li>

                <!--- Editar (Es lo que estoy usando para el registro de personas,socios,usuarios) -->
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-book-alt"></i>
                        <span>@lang('translation.Administer')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('personas.index') }}">@lang('translation.People')</a></li>
                        <li><a href="{{ route('propiedades.index')  }}">@lang('translation.Property')</a></li>
                        <li><a href="{{ route('users.index')  }}">@lang('translation.Users')</a></li>
                    </ul>
                </li>
                
                <!--- Editar -->

                <!--- Editar (Es lo que estoy usando para el registro de actividades) -->
                <li>
                    <a href="{{ route('actividades.index')  }}" class="waves-effect">
                        <i class="uil-calendar-alt"></i>
                        <span>@lang('translation.Activities')</span>
                    </a>
                </li>
                
                <!--- Editar -->

                <!--- Editar (Es lo que estoy usando para el registro de asistencias) -->

                <li>
                    <a href="{{ route('asistencias.index')  }}" class=" waves-effect">
                        <i class="uil-file-plus-alt"></i>
                        <span>@lang('translation.Assists')</span>
                    </a>
                </li>

                <!--- Editar -->

                 <!--- Editar (Es lo que estoy usando para el registro de consumos) -->

                 <li>
                    <a href="{{ route('consumos.index')  }}" class=" waves-effect">
                        <i class="uil-compass"></i>
                        <span>@lang('translation.Consumption')</span>
                    </a>
                </li>

                <!--- Editar -->

               <!--- Editar (Es lo que estoy usando para el registro de pagos) -->

               <li>
                    <a href="{{ route('pagos.index')  }}" class=" waves-effect">
                        <i class="uil-usd-circle"></i>
                        <span>@lang('translation.Payments')</span>
                    </a>
                </li>

                <!--- Editar -->  

                <!--- Editar (Es lo que estoy usando para el registro de multas) -->

               <li>
                    <a href="{{ route('multas.index')  }}" class=" waves-effect">
                        <i class="uil-usd-circle"></i>
                        <span>@lang('translation.Fines')</span>
                    </a>
                </li>

                <!--- Editar -->  

                <!--- Editar (Es lo que estoy usando para la emision de reportes) -->

               <li>
                    <a href="{{ route('reportes.index')  }}" class=" waves-effect">
                        <i class="uil-download-alt"></i>
                        <span>@lang('translation.Reports')</span>
                    </a>
                </li>

                

            
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->