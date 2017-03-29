<?php
namespace app\commands\tools;

class NewsParser {
	var $donors = [
		'kommersant_ru' => [
			'regex_domain' => '%kommersant.ru%uis',
			'regex_content' => '%<div id="divLetterBranding"[^>]+>(.*?)<\!-- RSS Link -->%uis',
			'regex_content1' => '%%uis',
			'charset' => 'windows-1251',
		],
		'vedomosti_ru' => [
			'regex_domain' => '%vedomosti.ru%uis',
			'regex_content' => '%<div class="b\-document__body b\-social__layout\-mutation">(.*?)</article>%uis',
			'regex_content1' => '%<div class="b-news-item__text b-news-item__text_one">(.*?)</article>%uis',
			'charset' => 'utf-8',
		],
		'bfm_ru' => [
			'regex_domain' => '%bfm.ru%uis',
			'regex_content' => '%<div class="about\-article">(.*?)<div class="share ">%uis',
			'regex_content1' => '%%uis',
			'charset' => 'utf-8',
		],
		'RNS_online' => [
			'regex_domain' => '%RNS.online%uis',
			'regex_content' => '%<div class="t\-body new">(.*?)<div class="c-row"%uis',
			'regex_content1' => '%%uis',
			'charset' => 'utf-8',
		],
		'Internet_cnews_ru' => [
			'regex_domain' => '%Internet.cnews.ru%is',
			'regex_content' => '%<section class="NewsBody _ga1_on_">(.*?)</section>%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
        		'GOV_cnews_ru' => [
			'regex_domain' => '%gov.cnews.ru%is',
			'regex_content' => '%<section class="NewsBody _ga1_on_">(.*?)</section>%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'Rbc_ru' => [
			'regex_domain' => '%Rbc.ru%is',
			'regex_content' => '%<div class="article__text">(.*?)<div class="article__tags">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'Therunet_com' => [
			'regex_domain' => '%Therunet.com%is',
			'regex_content' => '%<div class=\'content_body\'>(.*?)<footer class=\'content_footer\'>%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'Izvestia_ru' => [
			'regex_domain' => '%Izvestia.ru%is',
			'regex_content' => '%<div class="text_block" itemprop="articleBody"[^>]*>(.*?)<h6 class="comments_link">%uis',
			'regex_content1' => '%<div class="text_block js\-mediator\-article" itemprop="articleBody"[^>]*>(.*?)<h6 class="comments_link">%uis',
			'charset' => 'utf-8',
		],
		'Tass_ru' => [
			'regex_domain' => '%Tass.ru%is',
			'regex_content' => '%<div class="b\-material\-text__l">(.*?)<div class="b\-social\-buttons">%is',
			'regex_content1' => '%<div class="b\-material\-text__l js\-mediator\-article">(.*?)<div class="b\-social\-buttons">%is',
			'charset' => 'utf-8',
			'timeout' => '1',
		],
		'lenta_ru' => [
			'regex_domain' => '%lenta.ru%is',
			'regex_content' => '%<div class="b\-text clearfix" itemprop="articleBody">(.*?)<section class="b\-topic__socials">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'ria_ru' => [
			'regex_domain' => '%ria.ru%is',
			'regex_content' => '%<div class="article_full_content">(.*?)<div id="facebook\-userbar\-article\-end">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'gazeta_ru' => [
			'regex_domain' => '%gazeta.ru%is',
			'regex_content' => '%<div id="full_screen">(.*?)<div id="article_pants">%is',
			'regex_content1' => '%<div class="news\-body__text">(.*?)<div class="news\-body__social\-line js\-n\-share _padded">%is',
			'charset' => 'windows-1251',
		],
		'URA_Ru' => [
			'regex_domain' => '%URA.Ru%is',
			'regex_content' => '%<div class="incut\-story\-inner">(.*?)<div class="item-text l\-resize">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'regnum_ru' => [
			'regex_domain' => '%regnum.ru%is',
			'regex_content' => '%<div class="news_body" data\-id="[\d]+">(.*?)<div class="text_error_info">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'rusnovosti_ru' => [
			'regex_domain' => '%rusnovosti.ru%is',
			'regex_content' => '%<section class=\'post\-content\'>(.*?)</section>%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'Securitylab_ru' => [
			'regex_domain' => '%Securitylab.ru%is',
			'regex_content' => '%<article class="news\-full"[^>]*>(.*?)</article>%is',
			'regex_content1' => '%%is',
			'charset' => 'windows-1251',
		],
		'vc_ru' => [
			'regex_domain' => '%vc.ru%is',
			'regex_content' => '%<div itemprop="description">(.*?)<div class="b\-article__tags">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'sostav_ru' => [
			'regex_domain' => '%sostav.ru%is',
			'regex_content' => '%<div class="article\-body">(.*?)<div class="article\-tags">%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'adindex_ru' => [
			'regex_domain' => '%adindex.ru%is',
			'regex_content' => '%<div class="text_with_slider"[^>]*>(.*?)<div class="banners_news_pubs%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'3dnews_ru' => [
			'regex_domain' => '%3dnews.ru%is',
			'regex_content' => '%<div class="entry-body body-full[^\"]*" itemprop="articleBody">(.*?)<\!\-\- IST_PUB \-\->%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'ng_ru' => [
			'regex_domain' => '%ng.ru%is',
			'regex_content' => '%<article class="typical">(.*?)</article>%is',
			'regex_content1' => '%%is',
			'charset' => 'utf-8',
		],
		'novayagazeta_ru' => [
			'regex_domain' => '%novayagazeta.ru%is',
			'regex_content' => '%<div class="b-article g-content g-clearfix">(.*?)<div class="b-advert g-indented g-clearfix">%is',
			'regex_content1' => '%%is',
			'charset' => 'windows-1251',
		],
	];
/*
+	Kommersant.ru
+	Vedomosti.ru
+	RNS.online
+	Internet.cnews.ru
+	Rbc.ru
+	Therunet.com
+	Izvestia.ru
+	Tass.ru
+	lenta.ru
+	ria.ru
+	gazeta.ru

+	URA.Ru
+	regnum.ru
+	rusnovosti.ru
+	Securitylab.ru
+	vc.ru
+	sostav.ru
+	adindex.ru
+	3dnews.ru

+	ng.ru
+	novayagazeta.ru
*/
	function getPage($url, $post = array(), $add = array()) 
	{
		$ch = curl_init();
		if (count($post) > 0) {
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		if (count($add)) {
			curl_setopt_array($ch, $add);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$out = curl_exec($ch);
		curl_close($ch);
		return $out;
	}
	
	function clean_content($html, $k)
	{
		if( $k == 'kommersant_ru' ) {
			$html = preg_replace('%<div.*?<p class="b\-article__text">%is', '<p class="b-article__text">', $html);
		}
		elseif( $k == 'vedomosti_ru' ) {
			$html = preg_replace('%<div.*?<p align%is', '<p align', $html);
		}
		elseif( $k == 'Rbc_ru' ) {
			$html = preg_replace('%<blockquote.*?</blockquote>%is', ' ', $html);
			$html = preg_replace('%<div class="article__main-image">.*?<div class="article__text__overview">%is', '<div class="article__text__overview">', $html);
		}
		elseif( $k == 'Izvestia_ru' ) {
			$html = preg_replace('%<div class="img_block ">.*?</div>%is', ' ', $html);
			$html = preg_replace('%<div.*?<p%is', '<p', $html);
		}
		elseif( $k == 'gazeta_ru' ) {
			$html = preg_replace('%<table.*?</table>%is', ' ', $html);
			$html = preg_replace('%<article class="article">.*?</article>%is', ' ', $html);
			$html = preg_replace('%<div id="right" class="block">.*?<div id="article_body" class="block">%is', '<div id="article_body" class="block">', $html);
		}
		elseif( $k == 'ng_ru' ) {
			$html = preg_replace('%<p class="image_detail">.*?</p>%is', ' ', $html);
			$html = preg_replace('%<table class="leftImgDescription".*?</table>%is', ' ', $html);
			//$html = preg_replace('%<div id="right" class="block">.*?<div id="article_body" class="block">%is', '<div id="article_body" class="block">', $html);
		}
		elseif( $k == 'novayagazeta_ru' ) {
			$html = preg_replace('%<p class="g-justify-right">.*?</p>%is', ' ', $html);
			$html = preg_replace('%<blockquote.*?</blockquote>%is', ' ', $html);
			//$html = preg_replace('%<div id="right" class="block">.*?<div id="article_body" class="block">%is', '<div id="article_body" class="block">', $html);
		}
		$html = preg_replace('%<script[^>]*>.*?</script>%is', '', $html);
		$html = preg_replace('%<style[^>]*>.*?</style>%is', '', $html);
		$html = preg_replace('%<autor[^>]*>.*?</autor>%is', '', $html);
		
		$html = preg_replace('%<p[^>]*>%is', '☺', $html);
		$html = preg_replace('%</p>%is', '☻', $html);
		$html = preg_replace('%<u>%is', '♥', $html);
		$html = preg_replace('%</u>%is', '♦', $html);
		$html = preg_replace('%<b>%is', '♣', $html);
		$html = preg_replace('%</b>%is', '♠', $html);
		
		$html = preg_replace('%<[^>]+>%is', '', $html);
		$html = preg_replace('%\{\{.*?\}\}%is', '', $html);
		
		$html = preg_replace('%☺%is', '<p>', $html);
		$html = preg_replace('%☻%is', '</p>', $html);
		$html = preg_replace('%♥%is', '<u>', $html);
		$html = preg_replace('%♦%is', '</u>', $html);
		$html = preg_replace('%♣%is', '<b>', $html);
		$html = preg_replace('%♠%is', '</b>', $html);
		$html = str_replace('&lt;', '<', $html);
		$html = str_replace('&gt;', '>', $html);
		$html = str_replace('">', '', $html);

		$html = str_replace('\n', ' ', $html);
		$html = str_replace('\r', ' ', $html);
		$html = preg_replace('%[ ]{2,}%is', ' ', $html);
		
		return $html;
	}
	
	/*
	Замена строки *** на КОД***КОД
	где $str - строка в которой ищется подстрока ***
		$substr - *** (искомая подстрока)
		$tag - КОД
	*/
	function tag_wrapper($str, $substr, $tag)
	{
		return str_replace($substr, $tag.$substr.$tag, $str);
	}
	
	function parsePage($url) 
	{
		$options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_USERAGENT      => "spider", // who am i
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
		);
		
		foreach ( $this->donors as $k => $v ) {
			if( preg_match($v['regex_domain'], $url) ){
				
				if( isset($v['timeout']) && !empty($v['timeout']) ) sleep($v['timeout']);
				
				$page = $this->getPage($url,array(),$options);
				if( $k == 'gazeta_ru' ) {
					if( preg_match( '%<meta charset="windows\-1251" />%is', $page ) ) {
						$page = mb_convert_encoding($page, "utf-8", $v['charset']);
					}
				} else {
					if( $v['charset'] != 'utf-8' ) 
						$page = mb_convert_encoding($page, "utf-8", $v['charset']);
				}
				//print_r($page);die();
				
				if( preg_match($v['regex_content'], $page, $match) ) {
					//die('da');
					$content = $match[1];
					$content = $this->clean_content($content, $k);
					return $content;
				} elseif( preg_match($v['regex_content1'], $page, $match) ) {
					if (isset($match[1])) {
						$content = $match[1];
						$content = $this->clean_content($content, $k);
						return $content;
					}else{
						return;
					}
					
				}
			}
		}
	}
}




