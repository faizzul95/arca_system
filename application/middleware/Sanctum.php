<?php

# application/middleware/Sanctum.php

class Sanctum implements Luthier\MiddlewareInterface
{
	public function run($args)
	{
		if (!isLoginCheck()) {
			if (isAjax())
				return response(['code' => 401, 'message' => 'Login is required!'], HTTP_UNAUTHORIZED);
			else
				redirect('', true);
		}
	}
}
