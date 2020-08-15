<?php

namespace App\Models\TreeBinary;

use App\Models\TreeBinary;

class Task2 extends Store {

	protected const LEVEL_NAMBER = 5;

	public function __construct() {
		if (!parent::prepare() || !parent::build($this->leafNumberLast())) {
			throw new \Exception('Tree generation error');
		}
	}

	protected function leafNumberLast(): int {
		return pow(TreeBinary::BASE_NUMBER, self::LEVEL_NAMBER) - 1;
	}
	
	public function itemsById(int $id): array {
		$leaf_number_last = $this->leafNumberLast();
		if ($id <= 0) {
			return [];
		}
		if ($id > $leaf_number_last) {
			throw new \Exception('ID is very large. Max ' . $leaf_number_last);
		}
		
		$item = TreeBinary::find($id);
		if (empty($item->id) || empty($item->path)) {
			return [];
		}
		
		$items_down_patern = $item->path . TreeBinary::PATH_SEPARATOR;
		$items_top_ids = explode(TreeBinary::PATH_SEPARATOR, $item->path);
		unset($items_top_ids[count($items_top_ids) - 1]);
		
		return TreeBinary::where(function ($q) use ($items_down_patern, $items_top_ids) {
				$q->orWhere('path', 'LIKE', $items_down_patern . '%');
				foreach($items_top_ids as $item_top_id) {
					$q->orWhere('id', $item_top_id);
				}
			})->where('id', '<>', $item->id)->get()->toArray();
	}
}
