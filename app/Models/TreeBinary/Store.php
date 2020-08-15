<?php

namespace App\Models\TreeBinary;

use App\Models\TreeBinary;

abstract class Store {
	
	protected const ROOT_NUMBER = 1;
	protected const ROOT_LEVEL = 1;
	protected const STORE_ITEMS_COUNT = 500;
	private const ROW_FIRST_DATA = [
		'parent_id' => null,
		'position' => null,
		'path' => self::ROOT_NUMBER,
		'level' => self::ROOT_LEVEL,
	];
	
	protected function prepare(): bool {
		$tree = new TreeBinary;
		$tree->query()->truncate();
		$tree->fill(self::ROW_FIRST_DATA);

		return $tree->save();
	}
	
	protected function build(int $last_number): bool {
		if ($last_number <= self::ROOT_NUMBER) {
			return false;
		}

		$items_count = $last_number - self::ROOT_NUMBER;
		$leaf_id = self::ROOT_NUMBER;
		
		for ($item_index = 1; $item_index <= $items_count; $item_index++) {
			$leaf_id += self::ROOT_NUMBER;
			
			$tree = new TreeBinary();
			$tree->id = $leaf_id;
			$leaf = $tree->leafById();
			if (empty($leaf)) {
				return false;
			}
			
			$leafs[] = $leaf;
			if ((count($leafs) >= self::STORE_ITEMS_COUNT || $item_index == $items_count) &&
				!$this->store($leafs)
			) {
				return false;
			}
		}

		return true;
	}
	
	protected function store(array &$data): bool {
		$result = TreeBinary::insert($data);
		$data = [];
		
		return $result;
	}
	
	abstract protected function leafNumberLast(): int;
}
