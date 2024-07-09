<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
        <div class="search-element">
        </div>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{asset('')}}img/avatar/avatar-1.png" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{Auth::user()->name}}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    @role('Admin')
                        {{Auth::user()->email}}
                    @endrole
                    @role('Mahasiswa')
                        {{Auth::user()->kios}}
                    @endrole
                </div>
                <div class="dropdown-divider"></div>
                <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item has-icon text-danger" style="cursor: pointer">
                        <i class="fas fa-sign-out-alt mt-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>