<?php
namespace quarsintex\quartronic\qcore;

class QExternManager extends QSource
{
    protected function getConnectedProperties()
    {
        return [
            'configDir' => &self::$Q->params['configDir'],
            'runtimeDir' => &self::$Q->params['runtimeDir'],

        ];
    }

    public function initExtDirs()
    {
        foreach ([
            $this->configDir,
            $this->runtimeDir,
        ] as $dirPath) {
            if (!file_exists($dirPath)) mkdir($dirPath);
        }
    }
}