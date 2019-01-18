<footer id="footer">
	<footer class="row">
		<div class="col-md-4 offset-md-1 footer-desc">
			<span class="text-spotify"><%= site_name %></span>
			<p>
				<%= __("FOOTER_DESC") %>
			</p>
		</div>

		<div class="col-md-2 footer-nav">
			<ul>
				<% for (i in views) { %>
					<li>
		                <a href="<%= views[i].url %>"><%= __(views[i].name) %></a>
		            </li>
				<% } %>
			</ul>
		</div>

		<div class="col-md-4 footer-extra">
			<ul>
				<li class="copyright-item">&copy; Copyright 2017 <%= site_name %></li>
				<li><a href="#">Contact us</a></li>
				<li><a href="#">Terms of Service</a></li>
				<li><a href="#">Data Privacy Policy</a></li>
			</ul>
		</div>
	</div>
</footer>

<script>
	$(document).ready(function() {
		$("a[localeChange]").click(function() {
			$.cookie("user_locale", $(this).attr("localeChange"));
			location.reload();
		});

		var view = "<%= view %>";
		$("#main-nav > li").each(function() {
			if ($(this).attr("view") == view) {
				$(this).addClass("active");
			}
		});
		
		//$("body").css("padding-bottom", ($("#footer").height() + 10) + "px");
	});
</script>