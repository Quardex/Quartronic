<?php
namespace quarsintex\quartronic\qwidgets;

class QPagination extends \quarsintex\quartronic\qcore\QWidget
{
    public $total;
    public $currentPage;
    public $pageSize = 10;

    public function run()
    {
        $this->pageCount = ceil($this->total / $this->pageSize);
        $this->existPrev = $this->currentPage > 1;
        $this->existNext = $this->currentPage < $this->pageCount;
    }
}

?>