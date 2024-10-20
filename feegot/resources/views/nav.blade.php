<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('homepage') }}">FeeGo!</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('homepage') }}">Home</a>
                </li>
                @if( $user->role == 0 )
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('validate-payments') }}">Validation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manage-fees') }}">Fees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manage-classrooms') }}">Classroom</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manage-users') }}">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manage-students') }}">Students</a>
                </li>
                @endif

                @if( $user->role == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('verify-payments') }}">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manage-students') }}">Students</a>
                    </li>
                @endif

                @if( $user->role == 2)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('payment-history') }}">History</a>
                    </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        @if (Auth::check())
                            <span class="ms-2">{{ Auth::user()->name }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-fw"></i> Log Out
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>