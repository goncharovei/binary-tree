<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TreeBinary;

class Task2 extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tree-binary:task2 {id}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The condition 2';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$id = filter_var($this->argument('id'), FILTER_VALIDATE_INT);
		if (empty($id)) {
			$this->error('Input data is incorrect');
			return Command::FAILURE;
		}
		
		$runtime_start = microtime(true);
		$items = (new \App\Models\TreeBinary\Task2)->itemsById($id);
		if (empty($items)) {
			$this->error('Something went wrong!');
			return Command::FAILURE;
		}
		$runtime_end = round(microtime(true) - $runtime_start, 4);
		
		$message = 'Success';
		$message .= "\n";
		$message .= 'Execution time ' . $runtime_end. ' seconds';
		$message .= "\n";
		$message .= $this->itemsVerbose($items);
		
		$this->info($message);
		
		return Command::SUCCESS;
	}
	
	protected function itemsVerbose(array $items): string {
		if (empty($items)) {
			return '';
		}
		
		$items = array_map(function($val){
			return implode("\t", $val);
		}, $items);
		$head = [
			'id',
			'parent_id',
			'position',
			'path',
			'level'
		];
		
		return implode("\t", $head) . "\n" . implode("\n", $items);
	}

}
