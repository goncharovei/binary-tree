<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Task1 extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tree-binary:task1
			{parent_id}
			{position}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The condition 1';

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
		$arguments = $this->normalization($this->arguments());
		if (empty($arguments['parent_id']) || empty($arguments['position']) ||
			count($arguments) != 2
		) {
			$this->error('Something went wrong!');
			return Command::FAILURE;
		}
		
		$runtime_start = microtime(true);
		$is_success = (new  \App\Models\TreeBinary\Task1($arguments))->execute();
		$runtime_end = round(microtime(true) - $runtime_start, 4);
		
		$message = $is_success ? 'Success' : 'Fail';
		if ($is_success) {
			$message .= "\n";
			$message .= 'Execution time ' . $runtime_end. ' seconds';
			$message .= "\n";
			$message .= $this->treeVerbose();
		}
		
		$this->info($message);
		
		return $is_success ? Command::FAILURE : Command::SUCCESS;
	}
	
	protected function normalization(array $data): array {
		$data = array_map('trim', $data);
		$data = array_map('intval', $data);
		
		return array_diff($data, ['', 0, null]);
	}
	
	protected function treeVerbose(): string {
		$items = \App\Models\TreeBinary::orderBy('id', 'asc')
               ->get()->toArray();
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
