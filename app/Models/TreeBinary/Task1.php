<?php

namespace App\Models\TreeBinary;

use App\Models\TreeBinary;

class Task1 extends Store {
	
	const LEAF_PARENT_ID_MAX = 64 - parent::ROOT_NUMBER; //64 = (2^7)/2
	protected $parent_id = 0;
	protected $position = 0;

	public function __construct(array $data) {
		if (empty($data['parent_id']) || empty($data['position'])) {
			throw new \Exception('Data is not correct');
		}

		$this->parent_id = $data['parent_id'];
		$this->position = $data['position'];
	}

	public function execute(): bool {
		if ($this->parent_id <= 0 || !$this->isValidPosition()) {
			return false;
		}

		$leaf_number_last = $this->leafNumberLast();
		if ($leaf_number_last <= $this->parent_id) {
			return false;
		}
		if ($this->parent_id > self::LEAF_PARENT_ID_MAX) {
			throw new \Exception('Parent_id is very large. Max ' . self::LEAF_PARENT_ID_MAX);
		}

		return parent::prepare() && parent::build($leaf_number_last);
	}

	protected function isValidPosition(): bool {
		if (empty($this->position)) {
			return false;
		}

		return $this->position === TreeBinary::POSITION_LEFT || $this->position === TreeBinary::POSITION_RIGHT;
	}

	protected function leafNumberLast(): int {
		return ($this->parent_id * TreeBinary::BASE_NUMBER) + ($this->position - 1);
	}

}
