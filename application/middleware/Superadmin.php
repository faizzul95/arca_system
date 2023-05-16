<?php

# application/middleware/Superadmin.php

class Superadmin implements Luthier\MiddlewareInterface
{
	public function run($args)
	{
		if (!isLoginCheck()) {
			if (isAjax())
				return response(['code' => 401, 'message' => 'Login is required!'], HTTP_UNAUTHORIZED);
			else
				redirect('', true);
		} else if (currentUserRoleID() != 1) {
			return response(['code' => 403, 'message' => 'Unauthorized: Access is denied'], HTTP_FORBIDDEN);
		}
	}
}
