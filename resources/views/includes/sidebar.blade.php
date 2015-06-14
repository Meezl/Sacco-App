<aside>
    <div id="sidebar" class="nav-collapse">
        <ul class="sidebar-menu" id="nav-accordion">
            <p class="centered">
                @if(Auth::user()->getAvatar())
                <a href="{{ asset('uploads/images/'. Auth::user()->getAvatar()->filename) }}">
                    <img src="{{ asset('uploads/images/'. Auth::user()->getAvatar()->getCropped()) }}" alt="{{ Auth::user()->getFullName() }}" class="img-responsive" />
                </a>
                @else
                <a href=""><span class="user-image glyphicon glyphicon-user fa-5x"></span></a></p>
                @endif

            <h5 class="centered">Welcome {{Auth::user()->last_name}}</h5>
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
                    <li><a href="{{ action('CampaignController@getIndex') }}">All</a></li>
                    <li><a href="{{ action('CampaignController@getNew') }}">Create New</a></li>
                    <!--
                    <li><a href="">Trash</a></li>
                    -->
                </ul>
            </li>
            <li class="sub-menu">
                <a href="#">
                    <i class="glyphicon glyphicon-envelope"></i>
                    <span>Messages</span>
                </a>
                <ul class="sub">
                    <li><a href="{{ action('MessageController@getIndex') }}">Inbox</a></li>
                    <li><a href="{{ action('MessageController@getOutbox') }}">Sent</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="#">
                    <i class="glyphicon glyphicon-phone"></i>
                    <span>Contacts</span>
                </a>
                <ul class="sub">
                    <li><a href="{{ action('ContactController@getIndex') }}">All</a></li>
                    <li><a href="{{ action('ContactController@getNew') }}">Create New</a></li>
                    <li><a href="{{ action('CategoryController@getIndex') }}">Categories</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>User management</span>
                </a>
                <ul class="sub">
                    <li><a href="{{ action('UserController@getIndex') }}">All Users</a></li>
                    <li><a href="{{ action('UserController@getRegister') }}">Add User</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ action('AccountController@getIndex') }}">
                    <i class="fa fa-cogs"></i>
                    <span>Account Settings</span>
                </a>                
            </li>
        </ul>
    </div>
</aside>