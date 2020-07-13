@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            Menu
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    @if ($vc_user->role === 'user')
                    <li class="{{ ($path[0] === 'documents')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('documents.index')}}">
                            <i class="fas fa-receipt"></i><span>Comprobantes</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'retentions')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('retentions.index')}}">
                            <i class="fas fa-receipt"></i><span>Retenciones</span>
                        </a>
                    </li>
                        <li class="{{ ($path[0] === 'perceptions')?'nav-active':'' }}">
                            <a class="nav-link" href="{{route('perceptions.index')}}">
                                <i class="fas fa-receipt"></i><span>Percepciones</span>
                            </a>
                        </li>
                    <li class="{{ ($path[0] === 'dispatches')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('dispatches.index')}}">
                            <i class="fas fa-receipt"></i><span>Guías de remisión</span>
                        </a>
                    </li>
                    <li class="{{ ($path[0] === 'summaries')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('summaries.index')}}">
                            <i class="fas fa-file-alt"></i><span>Resúmenes</span>
                        </a>
                    </li>
                    {{--<li class="{{ ($path[0] === 'summaries')?'nav-active':'' }}">--}}
                        {{--<a class="nav-link" href="{{route('summaries.index')}}">--}}
                            {{--<i class="fas fa-list"></i><span>Resúmenes</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li class="{{ ($path[0] === 'companies')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('companies.create')}}">
                            <i class="fas fa-building"></i><span>Empresa</span>
                        </a>
                    </li>
                    @endif
                    @if ($vc_user->role === 'admin')
                    <li class="{{ ($path[0] === 'users')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('users.index')}}">
                            <i class="fas fa-users"></i><span>Usuarios</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>