<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreeBinary extends Model {
	
	public const BASE_NUMBER = 2;
	public const POSITION_LEFT = 1;
	public const POSITION_RIGHT = 2;
	public const PATH_SEPARATOR = '.';
	
	public $timestamps = false;
	protected $table = 'tree_binary';
	protected $fillable = [
		'parent_id',
		'position',
		'path',
		'level'
	];

	public function leafById(): array {
		if (empty($this->id) || !empty($this->exists)) {
			return [];
		}
		
		$result = [
			'parent_id' => $this->leafParentId(),
			'position' => $this->leafPosition(),
			'level' => $this->leafLevel(),
			'path' => $this->leafPath(),
		];
		
		return array_diff($result, ['', 0, null]);
	}

	protected function leafParentId(): int {
		return empty($this->id) ? 0 : floor($this->id / self::BASE_NUMBER);
	}

	protected function leafPosition(): int {
		if (empty($this->id)) {
			return 0;
		}

		return $this->id % self::BASE_NUMBER == 0 ? self::POSITION_LEFT : self::POSITION_RIGHT;
	}

	protected function leafLevel(): int {
		if (empty($this->id)) {
			return 0;
		}

		return ceil(log(($this->id + 1) / self::BASE_NUMBER, self::BASE_NUMBER)) + 1;
	}

	protected function leafPath(): string {
		if (empty($this->id)) {
			return '';
		}
		
		$leaf_level = $this->leafLevel();
		if (empty($leaf_level)) {
			return '';
		}
		
		$leaf_path = [$this->id];
		for ($leaf_level_index = 0; $leaf_level_index < $leaf_level - 1; $leaf_level_index++) {
			$leaf_path[] = floor($leaf_path[$leaf_level_index] / self::BASE_NUMBER);
		}

		$leaf_path = array_reverse($leaf_path);
		return !empty($leaf_path) && is_array($leaf_path) ? implode(self::PATH_SEPARATOR, $leaf_path) : '';
	}
	

}
