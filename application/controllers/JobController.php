<?php

class JobController extends WorkerController
{
	// Setting for that a listener could fork up to 10 workers
	public $workerMaxNum = 10;

	// Enable text log writen into specified file for listener and worker
	public $logPath = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'queue-worker.log';

	// Initializer
	protected function init()
	{
		library('myjobs');
	}

	// Worker
	protected function handleWork()
	{
		$job = $this->myjobs->getJob();

		// return `false` for job not found, which would close the worker itself.
		if (!$job)
			return false;

		// Your own method to process a job
		$this->myjobs->processJob($job);

		// return `true` for job existing, which would keep handling.
		return true;
	}

	// Listener
	protected function handleListen()
	{
		// Your own method to detect job existence
		// return `true` for job existing, which leads to dispatch worker(s).
		// return `false` for job not found, which would keep detecting new job
		// return $this->myjobs->exists();
		$this->handleWork();
	}
}
