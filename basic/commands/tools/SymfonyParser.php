<?php

namespace app\commands\tools;

use Symfony\Component\DomCrawler\Crawler;

class SymfonyParser extends Crawler implements ParserInterface
{
	/**
	 * @inheritdoc
	 */
	public function in($content, $content_type)
	{
		$this->addContent($content, $content_type);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function find($pattern)
	{
		return $this->filter($pattern)->getIterator()->getArrayCopy();
	}

	/**
	 * @inheritdoc
	 */
	public function findSrc($pattern)
	{
		return $this->filter($pattern)->extract(array('src'));
	}

	/**
	 * @inheritdoc
	 */
	public function findHref($pattern)
	{
		return $this->filter($pattern)->extract(array('href'));
	}

	/**
	 * @inheritdoc
	 */
	public function findAlt($pattern)
	{
		return $this->filter($pattern)->extract(array('alt'));
	}

	/**
	 * @inheritdoc
	 */
	public function findHtml($pattern)
	{	
		return $this->filter($pattern)->each(function ($node) {
         	return $node->html();          
        });
	}

}
