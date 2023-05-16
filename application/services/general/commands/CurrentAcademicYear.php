<?php

namespace App\services\general\commands;

class CurrentAcademicYear
{
	/**
	 * The console command task name.
	 *
	 * @var string
	 */
	protected $taskName = 'Academic Year';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set scheduled to check & update current academic year';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle($scheduler): void
	{
		$scheduler->call(function () {
			print "[" . timestamp('d/m/Y h:i A') . "]: {$this->taskName} currently is running\n";
			app('App\services\general\processor\CurrentAcademicYearStatusProcessor')->execute();
		})
			->onlyOne()
			->before(function () {
				print "[" . timestamp('d/m/Y h:i A') . "]: Job {$this->taskName} Started\n";
			})
			->then(function () {
				print "[" . timestamp('d/m/Y h:i A') . "]: Job {$this->taskName} Finished\n\n";
			})->daily();
	}
}
