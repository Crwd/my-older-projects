<div class="row">
            <div class="small-12 column">
                <nav class="top-bar" data-topbar role="navigation" data-options="back_text: <i class='fa fa-chevron-left'></i> Back; mobile_show_parent_link: false;">
                    <ul class="title-area">
                        <li class="name">
                            <a href="index.php">
                                <img src="images/logo.png" alt="logo">
                            </a>
                        </li>
                        <li class="toggle-topbar menu-icon">
                            <a href="#"><span>Menu</span></a>
                        </li>
                    </ul>

                    <section class="top-bar-section">
                        <ul class="right">
                            <li class="has-dropdown">
                                <a href="#">Home</a>
                                <ul class="dropdown">
                                    <li>
                                        <a href="?site=blog">Blog</a>
                                    </li>

                                    <li>
                                        <a href="?site=support">Support</a>
                                    </li>
									
                                    <?php if(!$secure_login->is_loggedin()) {?>
                                    <li>
                                        <a href="?site=login">Login</a>
                                    </li>

                                    <li>
                                        <a href="?site=forgot-password">Forgot Password</a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>

                            <li>
                                <a href="?site=plans">Plans &amp; Pricing</a>
                            </li>

                            <li>
                                <a href="?site=features">Features</a>
                            </li>

                            <li>
                                <a href="?site=about">About</a>
                            </li>

                            <li>
                                <a href="?site=contact">Contact</a>
                            </li>
                            <?php 
							$logged = false;
							if($secure_login->is_loggedin()) {
								$logged = true;
								if($user->getUserinfo("rank", $user->getUsername()) > 0) {?>
                            	<li>
                                    <a href="/admin">Admin</a>
                                </li>
                            <?php }} ?>
							
                            <li class="has-form">
								<?php 
								if($logged) {
									echo '<a href="?loggedout=' . time() . '" class="button">Logout</a>';
								} else {
									echo '<a href="?site=signup" class="button">Sign Up</a>';
								}
								?>
                            </li>
                        </ul>
                    </section>

                </nav>
            </div>
        </div>