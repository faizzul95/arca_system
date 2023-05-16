<?php

namespace App\services\general\commands;

class StatusActivityComplete
{
	/**
	 * The console command task name.
	 *
	 * @var string
	 */
	protected $taskName = 'Update Status Activity';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set scheduled to update status activity';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle($scheduler): void
	{
		$scheduler->call(function () {
			print "[" . timestamp('d/m/Y h:i A') . "]: {$this->taskName} currently is running\n";
			app('App\services\general\processor\ActivityCompletedStatusProcessor')->execute();
		})
			->onlyOne()
			->before(function () {
				print "[" . timestamp('d/m/Y h:i A') . "]: Job {$this->taskName} Started\n";
			})
			->then(function () {
				print "[" . timestamp('d/m/Y h:i A') . "]: Job {$this->taskName} Finished\n\n";
			})->everyMinute();
	}
}
