<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/8/8
 * Time: 14:47
 */

namespace Jiangxianli\GrabData;


class GrabData {

    private $url; //抓取的网页地址

    private $title ; //网页标题

    private $reg_link ; //匹配连接的正则

    private $reg_content; //匹配内容的正则

    private $depth = 0 ; //抓取深度

    private $content;

    private $reg_url ;

    public function __construct($url,$reg_content = null ,$depth=0,$reg_link=null,$reg_url=null){

        $this->url = $url;
        $this->reg_content = $reg_content;
        $this->depth = $depth;
        $this->reg_link = $reg_link;
        $this->reg_url = $reg_url;
    }

    public function getTitle($content = null){

        $content = $content ? $content :  $this->content;

        if($content){

            if(preg_match ('/<title>(.*)<\/title>/i' , $content, $matches)){

                $this->title = $matches[1];
            }
        }

        return $this->title;
    }

    public function getContent($depth=0,$callback){

        $this->content = CURL::get($this->url);
        if($this->reg_url && preg_match($this->reg_url,$this->url,$matches)){

            self::getTitle($this->content);

            if($this->reg_content){

                $html = SimpleHtml::str_get_html($this->content);

                call_user_func($callback, $this->url,$this->title,$html->find('.content',0)->innertext);

                return null;
//                if(preg_match ($this->reg_content , $this->content, $matches)) {
//
//                    call_user_func($callback, $this->url,$this->title,$matches);
//
//                    return null;
//                }

            }

//            call_user_func($callback, $this->url,$this->title,$this->content);
        }



       if($depth < $this->depth){

            $links = self::getCurPageLinks($this->content);

            foreach($links as $link){
                \Log::info($link);

                $this->url = self::addDomainForURL($this->url,$link);

                self::getContent($depth+1,$callback);
            }

        }

    }

    public function getCurPageLinks($content=null){

        $content = $content ? $content : $this->content;

        if(preg_match_all($this->reg_link,$content,$matches)){

           return $matches[1];
        }else{

            return [];
        }

    }


    public function addDomainForURL($domain_url,$link){

        $url_parse = parse_url($domain_url);


        if(starts_with($link,'http://') || starts_with($link,'https://') || starts_with($link,$url_parse['host'])){

            return $link;

        }

        return  $url_parse['scheme'].'://'.$url_parse['host'].$link;

    }



} 