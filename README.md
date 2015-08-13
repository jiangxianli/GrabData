# GrabData

抓取网页数据。

## 安装

环境要求：PHP     >= 5.4.0
          Laravel >= 4.2.0

1. 在composer.json中添加 "jiangxianli/grab-data":"dev-master".

  ```json
  "require": {
  	  "laravel/framework": "4.2.*",
  	  "..."
  	   "jiangxianli/grab-data":"dev-master"
  },

  ```

2. 在app.php中添加'Jiangxianli\GrabData\GrabDataServiceProvider',， 并设置别名'GrabData'          => 'Jiangxianli\GrabData\GrabData',

  ```php
  <?php

  'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		......
		'Jiangxianli\GrabData\GrabDataServiceProvider',

	),
  'aliases' => array(
		'App'               => 'Illuminate\Support\Facades\App',
		......
		'GrabData'          => 'Jiangxianli\GrabData\GrabData',

	),
  ...
  ```


3.composer update -VVV


## 使用

  ```php
  <?php

      //开始抓取页面URL
      $url = 'http://www.lookmw.cn/';

      //获取抓取页面指定内容，类似于Jquery中的用法
      $content_reg = '.content';

      //抓取深度
      //假如depth = 1,则在抓取$url页面的内容后，会对$url页面中
      //匹配 $link_reg 的所有超链接中正则匹配$url_reg的页面深度
      //抓取,回调的URL则会新页面的URL。
      //depth ＝ 2 、3 、4 .... 以此类推
      $detph = 0;

      //抓取页面的超链接正则匹配
      //$depth > 0 时 有效
      $link_reg = '/<a.*href="(.*[0-9]+.html)".*>.*<\/a>/i';

      //下一级抓取URL正则匹配
      $url_reg = '/(.*[0-9]+.html)/i';

      $grab_data  = new Jiangxianli\GrabData\GrabData($url,$content_reg,$depth,$link_reg,$url_reg);

      //抓取内容，回调
      $grab_data->getContent(0,function($url,$title,$content){

            //被抓取页面url
            echo $url;

            //被抓取页面标题
            echo $title;

            //被指定抓取的内容
            echo $content;

      });


  ```
## License

MIT
