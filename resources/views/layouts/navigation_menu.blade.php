<ul class="navigation-menu">

    <!-- Dashboard -->
    <li class="has-submenu">
        <a href="/"><i class="ti-home"></i>Dashboard</a>
    </li>

    <li class="has-submenu">
        <a href="{{ action('SmscampaignController@index') }}"><i class="ti-share"></i>Campagnes</a>
        <ul class="submenu">
            <li>
                <ul>
                    <li><a href="{{ action('SmscampaignController@index') }}">Lister</a></li>
                    <li><a href="{{ action('SmscampaignController@create') }}">Nouvelle</a></li>
                </ul>
            </li>

        </ul>
    </li>

</ul>
