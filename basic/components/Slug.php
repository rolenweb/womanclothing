<?php

namespace app\components;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use dosamigos\transliterator\TransliteratorHelper;

class Slug extends Behavior
{
	public $in_attribute = 'title';
	public $out_attribute = 'slug';
	public $translit = false;

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'
		];
	}	

	public function getSlug( $event )
	{
		if ( empty( $this->owner->{$this->out_attribute} ) ) {
			$this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->in_attribute} );
		} else {
			$this->owner->{$this->out_attribute} = $this->generateSlug( $this->owner->{$this->out_attribute} );
		}
	}

	private function generateSlug( $slug )
	{
		$slug = $this->slugify( $slug );
		if ( $this->checkUniqueSlug( $slug ) ) {
			return $slug;
		} else {
			for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '-' . $suffix ); $suffix++ ) {}
			return $new_slug;
		}
	}

	private function slugify( $slug )
	{
		if ( $this->translit ) {
			return Inflector::slug( TransliteratorHelper::process( $slug ), '-', true );
		} else {
			return Inflector::slug( $slug, '-', true );
		}
	}

	private function checkUniqueSlug( $slug )
	{
		$pk = $this->owner->primaryKey();
		$pk = $pk[0];

		$condition = [
			'and',
				[
					$this->out_attribute => $slug
				],
				[
					'parent_id' => $this->owner->parent_id,
				]
		];
		if ( !$this->owner->isNewRecord ) {
			$condition = [
				'and',
					[
						$this->out_attribute => $slug
					],
					[
						'parent_id' => $this->owner->parent_id,
					],
					[
						'!=', $pk, $this->owner->{$pk}
					]
			];	
		}

		return !$this->owner->find()
			->where($condition)
			->one();
	}
}