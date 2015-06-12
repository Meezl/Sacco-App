<aside>
    <div id="sidebar" class="nav-collapse">
        <ul class="sidebar-menu" id="nav-accordion">
            <p class="centered"><a href=""><span class="user-image glyphicon glyphicon-user fa-5x"></span></a></p>
            <h5 class="centered">Welcome jameskmb</h5>
            <li class="mt">
                <a class="" href="{{ action('DashBoardController@getIndex') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="#">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span>Campaigns</span>
                </a>
                <ul class="sub">
                    <li><a href="">All</a></li>
                    <li><a href="">Create New</a></li>
                    <li><a href="">Trash</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="glyphicon glyphicon-envelope"></i>
                    <span>Messages</span>
                </a>
                <ul class="sub">
                    <li><a href="">Inbox</a></li>
                    <li><a href="">Sent</a></li>
                    <li><a href="">Trash</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript;;">
                    <i class="fa fa-user"></i>
                    <span>User management</span>
                </a>
                <ul class="sub">
                    <li><a href="">All Users</a></li>
                    <li><a href="{{ action('Auth\AuthController@getRegister') }}">Add User</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-cogs"></i>
                    <span>Account Settings</span>
                </a>
                <ul class="sub">
                    <li><a href="">General</a></li>
                    <li><a href="">Notifications</a></li>
                </ul>
            </li>
        </ul>
    </div>
</aside>