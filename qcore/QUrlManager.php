<?php
namespace quarsintex\quartronic\qcore;

class QUrlManager extends QSource
{
    protected function getConnectedProperties()
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
            $url = preg_replace('/\?.*$/', '', $url);
            $url = preg_replace('/[\/]?index$/', '', $url);
            $lastSymb = $url[strlen($url)-1];
            if ($section[0] == '.') {
                $section = preg_replace('/^.\//', '', $section);
                if ($lastSymb != '/') {
                    $url = explode('/', $url);
                    array_pop($url);
                    $url = implode('/', $url);
                }
            }
            if ($lastSymb != '/') $url.= '/';
            $url.= $section;
        }
        if ($params) $url.= '?'.http_build_query($params);
        if ($anchor) $url.= '#'.$anchor;
        return $url;
    }
}

?>