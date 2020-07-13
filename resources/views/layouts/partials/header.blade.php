<header class="header">
    <div class="logo-container">
        <a href="{{route('dashboard')}}" class="logo pt-2 pt-md-0">
            {{--@if($vc_company)--}}
                {{--@if($vc_company->logo)--}}
                    {{--<img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}" alt="Logo" />--}}
                {{--@endif--}}
            {{--@else--}}
                {{--<i class="fa fa-circle fa-3x"></i>--}}
            {{--@endif--}}
        </a>
        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="header-right">
        <span class="separator"></span>
        <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                    <div class="border rounded-circle text-center" style="width: 25px;"><i class="fas fa-user"></i></div>
                </figure>
                <div class="profile-info" data-lock-name="{{ $vc_user->email }}" data-lock-email="{{ $vc_user->email }}">
                    <span class="name">{{ $vc_user->name }}</span>
                    <span class="role">{{ $vc_user->email }}</span>
                </div>
                <i class="fa custom-caret"></i>
            </a>
            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        <a role="menuitem" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> @lang('app.buttons.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>