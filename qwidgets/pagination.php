<?php

namespace quarsintex\quartronic\qwidgets;

class Pagination extends \quarsintex\quartronic\qcore\QWidget
{
    protected $name = 'Pagination';
    public $total;
    public $currentPage;
    public $pageSize = 1;

    public function run()
    {
        $this->pageCount = ceil($this->total / $this->pageSize);
        $this->existPrev = $this->currentPage > 1;
        $this->existNext = $this->currentPage < $this->pageCount;
    }
}

?>