 <!-- ========== App Menu ========== -->
 <div class="app-menu navbar-menu">
 	<!-- LOGO -->
 	<div class="navbar-brand-box">
 		<!-- Dark Logo-->
 		<a href="javascript:void(0);" class="logo logo-dark">
 			<span class="logo-sm">
 				<img src="{{ asset('images/logo/logo_uitm.png') }}" alt="" height="22">
 			</span>
 			<span class="logo-lg">
 				<img src="{{ asset('images/logo/logo.png') }}" alt="" height="50">
 			</span>
 		</a>
 		<!-- Light Logo-->
 		<a href="javascript:void(0);" class="logo logo-light">
 			<span class="logo-sm">
 				<img src="{{ asset('images/logo/logo_uitm.png') }}" alt="" height="30">
 			</span>
 			<span class="logo-lg">
 				<img src="{{ asset('images/logo/uitm.png') }}" alt="" height="60">
 			</span>
 		</a>
 		<button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
 			<i class="ri-record-circle-line"></i>
 		</button>
 	</div>

 	<div id="scrollbar">
 		<div class="container-fluid">

 			<div id="two-column-menu"></div>
 			<ul class="navbar-nav" id="navbar-nav">
 				<li class="menu-title"><span data-key="t-menu">Menu</span></li>

 				<?php
					hasAccess();
					$menuActive = segment(1);
					$submenuActive = segment(2);
					$currentMenuUrl = uri_string();

					foreach (getMenu() as $menu) {
						$activeMenu = ($currentMenuUrl == $menu['menu_url']) ? 'active' : '';

						if (empty($menu['submenu'])) {
							echo '<li class="nav-item">
                                        <a class="nav-link menu-link ' . $activeMenu . '" href="' . url($menu['menu_url']) . '">
                                            ' . $menu['menu_icon'] . ' <span data-key="t-' . purify($menu['menu_title']) . '">' . purify($menu['menu_title']) . '</span>
                                        </a>
                                    </li>';
						} else {
							$showSubMenu = ($menuActive == $menu['menu_url']) ? 'show' : '';
							echo '<li class="nav-item">
                                    <a class="nav-link menu-link ' . $activeMenu . '" href="#sidebar' . $menu['menu_id'] . '" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                    ' . $menu['menu_icon'] . ' <span data-key="t-' . purify($menu['menu_title']) . '">' . purify($menu['menu_title']) . '</span>
                                    </a>
                                    
                                    <div class="collapse menu-dropdown mega-dropdown-menu ' . $showSubMenu . '" id="sidebar' . $menu['menu_id'] . '">
                                        <ul class="nav nav-sm flex-column">';

							foreach ($menu['submenu'] as $submenu) {
								$activeSubMenu = ($currentMenuUrl == $submenu['menu_url']) ? 'active' : '';

								echo ' <li class="nav-item">
                                            <a href="' . url($submenu['menu_url']) . '" class="nav-link ' . $activeSubMenu . '" data-key="t-' . purify($submenu['menu_title']) . '"> ' . purify($submenu['menu_title']) . ' </a>
                                        </li>';
							}

							echo '      </ul>
                                    </div>
                                </li>';
						}
					}
					?>

 				<?php if (currentUserRoleID() == 1 || currentUserRoleID() == 2) : ?>
 					<li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages"> System </span></li>

 					<li class="nav-item">
 						<a class="nav-link menu-link <?= ($currentSidebar == 'Management') ? 'active' : '' ?>" href="<?= url('management') ?>">
 							<i class="ri-settings-5-line"></i> <span data-key="t-management"> Management </span>
 						</a>
 					</li>

 					<?php if (currentUserRoleID() == 1) : ?>
 						<li class="nav-item">
 							<a class="nav-link menu-link <?= ($currentSidebar == 'Settings') ? 'active' : '' ?>" href="<?= url('rbac') ?>">
 								<i class="ri-list-settings-line"></i>
 								<span data-key="t-settings"> Settings </span>
 							</a>
 						</li>
 					<?php endif; ?>

 				<?php endif; ?>

 			</ul>
 		</div>
 		<!-- Sidebar -->
 	</div>
 </div>
 <!-- Left Sidebar End -->
 <!-- Vertical Overlay-->
 <div class="vertical-overlay"></div>