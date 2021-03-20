<?php
namespace
{
    function Q()
    {
        static $Q;
        if (!$Q) $Q = new \quarsintex\quartronic\qcore\QGlobalSingleton;
        return $Q::app();
    }
}

namespace quarsintex\quartronic\qcore
{
    class QRender extends QSource
    {
        protected $_js;
        protected $_jsFiles;
        protected $_css;
        protected $_cssFiles;
        protected $view;
        protected $_viewDir;
        protected $_sources;

        public $layout = 'layout';
        public $tplExtension = 'php';
        public $content;

        const POSITION_HEAD_BEGIN = 0;
        const POSITION_HEAD_END = 1;
        const POSITION_BODY_BEGIN = 2;
        const POSITION_BODY_END = 3;

        protected function getConnectedProperties()
        {
            return [
                'returnRender' => &self::$Q->params['returnRender'],
                'appDir' => &self::$Q->router->appDir,
                'webDir' => &self::$Q->router->webDir,
                'webPath' => &self::$Q->router->webPath,
                'subWebPath' => &self::$Q->router->subWebPath,
                'qRootDir' => &self::$Q->router->qRootDir,
            ];
        }

        function getViewDir()
        {
            if (!$this->_viewDir) {
                $this->_viewDir = $this->qRootDir;
                if ($this->appDir) $this->_viewDir.= '../../../'.$this->appDir.'/';
                $this->_viewDir.= 'qthemes/adminbsb/';
            }
            return $this->_viewDir;
        }

        public function getSources()
        {
            if (!$this->_sources) $this->_sources = new QAssetBundle($this->webDir, $this->webPath, $this->subWebPath);
            return $this->_sources;
        }

        public function getJsList()
        {
            return $this->_js;
        }

        public function getJsFileList()
        {
            return $this->_jsFiles;
        }

        public function getCssList()
        {
            return $this->_css;
        }

        public function getCssFileList()
        {
            return $this->_cssFiles;
        }

        public function registerJs($name, $js, $pos = self::POSITION_BODY_END)
        {
            $this->_js[$pos][$name] = $js;
        }

        public function registerJsFile($path, $pos = self::POSITION_BODY_END)
        {
            $registred = $this->sources->register('js/' . basename($path), $path);
            foreach ($registred as $targetPath => $sourcePath) {
                $this->_jsFiles[$pos][md5($targetPath)] = $this->sources->assetsPath . $targetPath;
            }
        }

        public function registerCss($name, $css, $pos = self::POSITION_HEAD_END)
        {
            $this->_css[$pos][$name] = $css;
        }

        public function registerCssFile($path, $pos = self::POSITION_HEAD_END)
        {
            $registred = $this->sources->register('css/' . basename($path), $path);
            foreach ($registred as $targetPath => $sourcePath) {
                $this->_cssFiles[$pos][md5($targetPath)] = $this->sources->assetsPath . $targetPath;
            }
        }

        public function registerFile($from, $category = '', $type = '', $pos = self::POSITION_BODY_END)
        {
            if ($category) $category.= '/';
            $storage = '';
            switch ($type) {
                case 'js':
                    $storage = &$this->_jsFiles;
                    break;

                case 'css':
                    $storage = &$this->_cssFiles;
                    break;
            }
            $targetPath = $category.basename($from);
            if ($storage)
                $storage[$pos][md5($targetPath)] = $this->sources->assetsPath . $targetPath;
            $this->sources->register($targetPath, $from);
        }

        public function registerDir($sourcePath, $targetPath)
        {
            $this->sources->register($targetPath, $sourcePath, true);
        }

        public function attachResources($pos)
        {
            $output = '';
            if (!empty($this->_cssFiles[$pos])) {
                foreach ($this->_cssFiles[$pos] as $file) {
                    $output .= "\n".'<link rel="stylesheet" type="text/css" href="' . $file . '">';
                }
            }

            if (!empty($this->_css[$pos])) {
                $output .= '<style>';
                foreach ($this->_css[$pos] as $code) {
                    $output .= $code;
                }
                $output .= '</style>';
            }

            if (!empty($this->_jsFiles[$pos])) {
                foreach ($this->_jsFiles[$pos] as $file) {
                    $output .= "\n".'<script src="' . $file . '"></script>';
                }
            }

            if (!empty($this->_js[$pos])) {
                $output .= '<script>';
                foreach ($this->_js[$pos] as $code) {
                    $output .= $code;
                }
                $output .= '</script>';
            }

            return $output;
        }

        public function run($view = '', $data = [], $layout = null)
        {
            if ($view) $this->view = $view;
            if (isset($layout)) $this->layout = $layout;

            $content = '';
            foreach ($data as $var => $value) {
                $$var = $value;
            }

            if ($this->view) {
                ob_start();
                include($this->viewDir . $this->view . '.' . $this->tplExtension);
                $this->content = ob_get_clean();
            } else {
                $this->content = $content;
            }

            if ($this->layout) {
                ob_start();
                include($this->viewDir . $this->layout . '.php');
                $output = ob_get_clean();
            } else {
                $output = $this->content;
            }

            $this->sources->export();

            if ($this->returnRender) {
                return $output;
            } else {
                echo $output;
            }
        }

        public function runPartial($view, $data = [])
        {
            $this->run($view, $data, '');
        }

        public function widget($name, $params = [])
        {
            $className = '\\quarsintex\\quartronic\\qwidgets\\' . $name;
            $widget = new $className($params);
            echo $widget->render();
        }

    }
}

?>