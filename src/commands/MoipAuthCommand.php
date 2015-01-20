<?php namespace SOSTheBlack\Moip\Commands;

use Moip;
use Illuminate\Console\Command;

class MoipAuthCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'moip:auth';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sets environment and credentials';

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$environment= $this->confirm('Sets environment [yes for production|no for Sandbox]') ? 'Moip' : 'Sandbox';
		$receiver 	= $this->ask('Enter the primary recipient of the '.$environment);
		$token 		= $this->ask('Enter the token of the '.$environment);
		$key 		= $this->secret('Enter the key of the '.$environment);
		$this->comment('Sending settings to the database');
		$moip = Moip::first();
		$moip->environment = $environment === 'Moip' ? 1 : 0;
		$moip->receiver = $receiver;
		$moip->token 	= $token;
		$moip->key 		= $key;
		$moip->save();
	}
}