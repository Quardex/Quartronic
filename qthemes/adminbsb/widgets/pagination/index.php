<?php if ($pageCount>1): ?>
<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_1_paginate">
    <ul class="pagination">
        <li class="paginate_button previous<?php if (!$existPrev) echo ' disabled';?>" id="DataTables_Table_1_previous"><a href="<?=$existPrev ? '?page='.($this->currentPage-1) : '#'?>" aria-controls="DataTables_Table_1" data-dt-idx="0" tabindex="0">Previous</a></li>
        <?php for($num = 1; $num<=$pageCount; $num++):?>
        <li class="paginate_button<?php if ($num == $this->currentPage) echo ' active';?>"><a href="?page=<?=$num?>" aria-controls="DataTables_Table_1" data-dt-idx="<?=$num?>" tabindex="0"><?=$num?></a></li>
        <?php endfor; ?>
        <li class="paginate_button next<?php if (!$existNext) echo ' disabled';?>" id="DataTables_Table_1_next"><a href="<?=$existNext ? '?page='.($this->currentPage+1) : '#'?>" aria-controls="DataTables_Table_1" data-dt-idx="7" tabindex="0">Next</a></li>
    </ul>
</div>
<?php endif?>