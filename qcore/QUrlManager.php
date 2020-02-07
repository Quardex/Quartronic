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
            if ($section[0] == '.') {
                $section = preg_replace('/^./', '', $section);
            }
            elseif ($url[strlen($url)-1] != '/') {
                $url = str_replace(array_pop(explode('/', $url)), '', $url);
            }
            $url.= $section;
        }
        if ($params) $url.= '?'.http_build_query($params);
        if ($anchor) $url.= '#'.$anchor;
        return $url;
    }
}
?>