<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse" id="site-navbar">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<a class="navbar-brand text-spotify" href="/">
		<div class="spotify-icon"></div><%= site_name %>
	</a>

	<div class="collapse navbar-collapse" id="navbarNav" style="margin-left:0px;">
		<ul class="navbar-nav mr-auto" id="main-nav">
			<% for (i in views) { %>
				<li class="nav-item" view="<%= i %>">
	                <a class="nav-link" href="<%= views[i].url %>"><%= __(views[i].name) %></a>
	            </li>
			<% } %>
        </ul>

        <ul class="navbar-nav">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img style="margin-top:-3px;margin-right:5px;" height="16px" src="/public/img/locales/<%= locale %>.png" /> <%= __("NAV_LANGUAGE") %>
				</a>

				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
					<% for (i in locales) { %>
						<a class="dropdown-item" style="cursor:pointer;" localeChange="<%= locales[i] %>">
							<img height="24px" src="/public/img/locales/<%= locales[i] %>.png" /> <%= __("LANG_" + locales[i].toUpperCase()) %>
						</a>
					<% } %>
				</div>
			</li>

        </ul>
	</div>
</nav>
