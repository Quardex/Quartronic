<?php
namespace quarsintex\quartronic\qcore;

class QUrlManager extends QSource
{
    protected function getConnectedParams()
    {
        return [
            'webPath' => &self::$Q->webPath,
            'currentUrl' => &self::$Q->request->url,
        ];
    }

    function route($section, $params = [], $anchor = '')
    {
        if ($section[0] == '/') {
            $url = $this->webPath . substr($section, 1);
        } else {
            $url = $this->currentUrl;
            if ($url[strlen($url)-1] != '/') $url.= '/';
            $url.= $section;
        }
        if ($params) $url.= '?'.http_build_query($params);
        if ($anchor) $url.= '#'.$anchor;
        return $url;
    }
}
?>