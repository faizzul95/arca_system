<header id="page-topbar">
	<div class="layout-width">
		<div class="navbar-header">
			<div class="d-flex">
				<button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
					<span class="hamburger-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</button>
			</div>

			<div class="d-flex align-items-center">
				<div class="ms-1 header-item d-none d-sm-flex">
					<button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
						<i class='bx bx-fullscreen fs-22'></i>
					</button>
				</div>
				<div class="ms-1 header-item d-none d-sm-flex">
					<button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
						<i class='bx bx-moon fs-22'></i>
					</button>
				</div>
				<div class="dropdown ms-sm-3 header-item topbar-user">
					<button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="d-flex align-items-center">
							<img id="header_user_avatar" class="rounded-circle header-profile-user" src="{{ currentUserAvatar() }}" alt="Header Avatar">
							<span class="text-start ms-xl-2">
								<span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ currentUserFullName() }}</span>
								<span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ currentUserProfileName() }}</span>
							</span>
						</span>
					</button>
					<div class="dropdown-menu dropdown-menu-end">
						<h6 class="dropdown-header">Profile</h6>

						<?php
						$profile = getAllUserProfile();
						foreach ($profile as $up) {
							// $current = ($up['profile_id'] == currentUserProfileID()) ? '<span class="badge bg-soft-success text-success mt-1 ml-2 float-end"> Current </span>' : '';
							$current = ($up['profile_id'] == currentUserProfileID()) ? 'style="color:green!important"' : '';
							$roleName = ($up['profile_id'] == currentUserProfileID()) ? '<span style="color:green!important">' . $up['role_name'] . '</span>' : $up['role_name'];

							$changeProfile = ($up['profile_id'] != currentUserProfileID()) ? 'onclick="changeProfile(' . $up['profile_id'] . ', ' . $up['user_id'] . ', \'' . $up['role_name'] . '\')"' : '';
							echo '<a class="dropdown-item" href="javascript:void(0);" ' . $changeProfile . '><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1" ' . $current . '></i> <span class="align-middle">' . purify($roleName) . '</span></a>';
						}
						?>

						<div class="dropdown-divider"></div>
						<?php
						$menuHeaderActive = segment(1);
						$submenuHeaderActive = segment(2);

						foreach (getMenu(1) as $menuHeader) {
							$activeMenu = ($menuHeaderActive == $menuHeader['menu_url']) ? 'active' : '';

							if (empty($menuHeader['submenu'])) {
								echo '<a class="dropdown-item" href="' . url($menuHeader['menu_url']) . '">
                                        ' . $menuHeader['menu_icon'] . ' 
                                        <span class="align-middle"> ' . $menuHeader['menu_title'] . ' </span>
                                    </a>';
							}
						}
						?>

						<a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>