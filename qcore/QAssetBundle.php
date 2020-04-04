<?php
namespace quarsintex\quartronic\qcore;

class QAssetBundle extends QSource
{
    protected $webDir;
    protected $webPath;
    protected $_assetsDir;
    protected $_assetsPath;
    protected $_fileList = [];
    protected $_dirList = [];

    public function __construct($webDir, $webPath)
    {
        $this->webDir = $webDir;
        $this->webPath = $webPath;
    }

    public function getAssetsSubDir() {
        return 'qassets/' . self::$Q->version . '/';
    }

    public function getAssetsDir() {
        if (!$this->_assetsDir) {
            $this->_assetsDir = $this->webDir.$this->assetsSubDir;
        }
        return $this->_assetsDir;
    }

    public function getAssetsPath() {
        if (!$this->_assetsPath) {
            $this->_assetsPath = $this->webPath.$this->assetsSubDir;
        }
        return $this->_assetsPath;
    }

    public function register($subWebPath, $sourcePath = '', $isDirectory = false)
    {
        if ($isDirectory) {
            $list = [$subWebPath => $sourcePath];
            $this->_dirList = array_merge($this->_dirList, [$subWebPath => $sourcePath]);
        } else {
            $list = &$subWebPath;
            if (!is_array($list)) {
                $list = [$subWebPath => $sourcePath];
            }
            $this->_fileList = array_merge($this->_fileList, $list);
        }
        return $list;
    }

    protected function copydir($from, $to, $rewrite = true) {
        if (is_dir($from)) {
            @mkdir($to);
            $d = dir($from);
            while (false !== ($entry = $d->read())) {
                if ($entry == "." || $entry == "..") continue;
                $this->copydir("$from/$entry", "$to/$entry", $rewrite);
            }
            $d->close();
        } else {
            if (!file_exists($to) || $rewrite)
                copy($from, $to);
        }
    }

    protected function rmdir($dir) {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->rmdir($obj) : unlink($obj);
            }
        }
        @rmdir($dir);
    }

    public function export()
    {
        $webDir = $this->assetsDir;
        if (!file_exists($webDir) && file_exists($dirname = dirname($webDir))) $this->rmdir($dirname);
        while ((!isset($c) && ($c=0) || $c++ < 20) && !file_exists($webDir)) {
            @mkdir($webDir, 0777, true);
            usleep(50);
        }
        foreach ($this->_fileList as $subPath => $sourcePath) {
            $curTargetPath = $webDir . $subPath;
            if (!file_exists($curTargetPath)) {
                if (!file_exists($dirname = dirname($curTargetPath))) {
                    @mkdir($dirname, 0777, true);
                }
                copy($sourcePath, $curTargetPath);
            }
        }
        foreach ($this->_dirList as $subPath => $sourcePath) {
            $curTargetPath = $webDir . $subPath;
            if (!file_exists($curTargetPath)) {
                $this->copydir($sourcePath, $curTargetPath);
            }
        }

    }
}