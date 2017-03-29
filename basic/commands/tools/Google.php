<?php
  namespace app\commands\tools;

  class Google
  {
    private $http;
    
    public function __construct()
    {
      $this->http = new http;
    }
    
    private function parse($content)
    {
      if (defined('DEV')) echo '[gsearch] Parse content' . PHP_EOL;
      
      $result = array();
      
      if (preg_match_all('#<li class="g">(.+?)<\/div><\/li>#is', $content, $matches))
      {
        foreach ($matches[1] as $block)
        {
          $item = array();
          
          if (preg_match('#<h3 class="r"><a href="\/url\?q=(.+?)\&amp\;#is', $block, $block_matches))
          {
            $item['url'] = $block_matches[1];
          }
          
          if (preg_match('#<h3 class="r">(.+?)<\/h3>#is', $block, $block_matches))
          {
            $item['title'] = html_entity_decode(strip_tags($block_matches[1]));
          }
          
          if (preg_match('#<span class="st">(.+?)<\/span>#is', $block, $block_matches))
          { // ???
            $item['description'] = html_entity_decode(strip_tags($block_matches[1]));
          }
          
          if (preg_match('#href="\/url\?q=(http:\/\/webcache\..+?)\&amp\;#is', $block, $block_matches))
          {
            $item['cache'] = rawurldecode($block_matches[1]);
          }
          
          $result[] = $item;
        }
      }
      elseif (preg_match_all('#<!--m-->(.+?)<!--n-->#is', $content, $matches))
      {
        foreach ($matches[1] as $block)
        {
          $item = array();
          
          if (preg_match('#<h3 class="r"><a href="(.+?)"#is', $block, $block_matches))
          {
            $item['url'] = $block_matches[1];
          }
          
          if (preg_match('#<h3 class="r">(.+?)<\/h3>#is', $block, $block_matches))
          {
            $item['title'] = strip_tags($block_matches[1]);
          }
          
          if (preg_match('#<span class="st">(.+?)<\/span>#is', $block, $block_matches))
          {
            $item['description'] = $block_matches[1];
          }
          
          if (preg_match('#href="(http:\/\/webcache\..+?)"#is', $block, $block_matches))
          {
            $item['cache'] = $block_matches[1];
          }
          
          $result[] = $item;
        }
      }
      else
      {
        i()->error->userWarning('Cant parse google response');
        return false;
      }
      
      return $result;
    }
    
    private function captcha()
    {
      $content = $this->http->response()->content();
      
      if (!preg_match('#src="(\/sorry\/image\?id=.*\&amp;hl=en)"#is', $content, $matches))
      {
        return false;
      }
      
      $captchaUrl   = 'https://www.google.com' . $matches[1];
      $captchaImage = $this->http->get($captchaUrl);
      
      if (defined('DEV')) echo '[gsearch] Get captcha image: ' .  $captchaUrl . PHP_EOL;
      
      $this->http->request()->content(array(
        'method'   => 'base64',
        'key'      => '???',
        'regsense' => 1,
        'body'     => base64_encode($captchaImage),
      ));
      
      $content = $this->http->post('http://anti-captcha.com/in.php');
      
      @list($status, $captchaId) = explode('|', $content);
      
      if ($status != 'OK')
      {
        if (defined('DEV')) echo '[gsearch] Cant create captcha request: ' . $status . PHP_EOL;
        
        i()->error->userWarning('Create captcha: ' . $status);
        return false;
      }
      
      if (defined('DEV')) echo '[gsearch] Captcha request success' . PHP_EOL;
      
      while (true)
      {
        usleep(500000);
        
        $this->http->request()->content(array(
          'key'    => '???',
          'action' => 'get',
          'id'     => $captchaId,
        ));
        
        $content = $this->http->post('http://anti-captcha.com/res.php');
        
        @list($status, $captchaText) = explode('|', $response);
        
        if ($status == 'CAPCHA_NOT_READY')
        {
          continue;
        }
        
        break;
      }
      
      if (defined('DEV')) echo '[gsearch] Captcha check status: ' . $status . PHP_EOL;
      
      if ($status != 'OK')
      {
        i()->error->userWarning('Request captcha: ' . $status);
        return false;
      }
      
      if (!preg_match('#\<input type="hidden" name="id" value="(.+?)"\>#is', $content, $matches))
      {
        return false;
      }
      
      $captchaFormId = $matches[1];
      
      $captchaForm = array(
        'id'       => $captchaFormId,
        'continue' => '',
        'captcha'  => $captchaText,
        'submit'   => 'ќтправить',
      );
      
      if (defined('DEV')) echo '[gsearch] Submit google captcha form' . PHP_EOL;
      
      $this->request('https://ipv4.google.com/sorry/CaptchaRedirect' . '?' . http_build_query($captchaForm, '', '&'));
    }
    
    private function request($url)
    {
      if (defined('DEV')) echo '[gsearch] Request url: ' . $url . PHP_EOL;
      
      $this->http->get($url);
      
      if ($this->http->response()->code() == 302)
      {
        $prevUrl = $url;
        $url     = $http->response()->header('Location');
        
        if (defined('DEV')) echo '[gsearch] Request redirect url: ' . $url . PHP_EOL;
        
        $this->http->request()->referer($prevUrl);
        $this->http->request()->userAgent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36');
        $this->http->get($url);
      }
    }
    
    public function search($text)
    {
      return $this->parse(file_get_contents(i()->config->get('app/path/temp') . 'test2.txt'));
      
      if (defined('DEV')) echo '[gsearch] Search text: ' . $text . PHP_EOL;
      
      $query = array
      (
        'q'      => $text,
        'start'  => 0,
        'num'    => 100,
        'hl'     => 'en',
        'ie'     => 'UTF-8',
        'gws_rd' => 'cr',
      );
      
      $searchUrl = 'https://www.google.com/search?' . http_build_query($query, '', '&');
      
      $this->request($searchUrl);
    
      if ($this->http->response()->code() != 200)
      { // Need captcha
        if (defined('DEV')) echo '[gsearch] Need captcha' . PHP_EOL;
        
        $this->captcha();
        
        if (!$this->http->response()->code() != 200)
        {
          if (defined('DEV')) echo '[gsearch] Request Error hack captcha' . PHP_EOL;
          
          i()->error->userWarning('Cant hack captcha');
          return false;
        }
      }
      
      if (defined('DEV')) echo '[gsearch] Parse google search response' . PHP_EOL;
      
      file_put_contents(i()->config->get('app/path/temp') . 'test2.txt', $this->http->response()->content());
      
      return $this->parse($this->http->response()->content());
    }
  }
?>