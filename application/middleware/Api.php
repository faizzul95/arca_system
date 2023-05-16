<?php

# application/middleware/Api.php

use App\middleware\core\traits\RateLimitingThrottleTrait;
use App\middleware\core\traits\XssProtectionTrait;

class Api implements Luthier\MiddlewareInterface
{
	use RateLimitingThrottleTrait;
	use XssProtectionTrait;

	public function run($args)
	{
		if (!isAjax()) {
			return response(['code' => 422, 'message' => 'API is only accessible via AJAX REQUEST!'], HTTP_UNPROCESSABLE_ENTITY);
		} else {
			if ($this->isXssAttack())
				return response(['code' => 422, 'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!'], HTTP_UNPROCESSABLE_ENTITY);
			else
				$this->isRateLimiting();
		}
	}
}
