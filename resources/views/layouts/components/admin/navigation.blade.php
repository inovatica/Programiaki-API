<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <li>
                <a href="{{route('admin.dashboard')}}">
                    <i class="fa fa-dashboard fa-lg fa-fw"></i> <span>{{ ucfirst(__('dashboard')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('games.index')}}">
                    <i class="fa fa-gamepad fa-lg fa-fw"></i> <span>{{ ucfirst(__('games')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('levels.index')}}">
                    <i class="fa fa-code-fork fa-lg fa-fw"></i> <span>{{ ucfirst(__('levels')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('objects.index')}}">
                    <i class="fa fa-cube fa-lg fa-fw"></i> <span>{{ ucfirst(__('objects')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('tags.index')}}">
                    <i class="fa fa-cubes fa-lg fa-fw"></i> <span>{{ ucfirst(__('tags')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('settings.list')}}">
                    <i class="fa fa-cogs fa-lg fa-fw"></i> <span>{{ ucfirst(__('settings')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('users.index')}}">
                    <i class="fa fa-user-circle-o fa-lg fa-fw"></i> <span>{{ ucfirst(__('users')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('avatars.index')}}">
                    <i class="fa fa-image fa-lg fa-fw"></i> <span>{{ ucfirst(__('avatars')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('institutions.list')}}">
                    <i class="fa fa-university fa-lg fa-fw"></i> <span>{{ ucfirst(__('institutions')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('groups.list')}}">
                    <i class="fa fa-users fa-lg fa-fw"></i> <span>{{ ucfirst(__('groups')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('tables.list')}}">
                    <i class="fa fa-tv fa-lg fa-fw"></i> <span>{{ ucfirst(__('tables')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('gamification.list')}}">
                    <i class="fa fa-line-chart fa-lg fa-fw"></i> <span>{{ ucfirst(__('gamification')) }}</span>
                </a>
            </li>
            <li>
                <a href="{{route('certification.list')}}">
                    <i class="fa fa-graduation-cap fa-lg fa-fw"></i> <span>{{ ucfirst(__('certification')) }}</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
