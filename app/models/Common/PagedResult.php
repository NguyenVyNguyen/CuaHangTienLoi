<?php 
require_once "PageItem.php";

class PagedResult {
    public $Page = 1;
    public $PageSize = 20;
    public $RowCount = 0;
    public $DataItems = [];

    public function getPageCount() {
        if ($this->PageSize == 0) return 1;
        return ceil($this->RowCount / $this->PageSize);
    }

    public function hasPreviousPage() {
        return $this->Page > 1;
    }

    public function hasNextPage() {
        return $this->Page < $this->getPageCount();
    }

    public function getDisplayPages($n = 5) {
        $result = [];

        $pageCount = $this->getPageCount();
        if ($pageCount == 0) return $result;

        $currentPage = $this->Page;
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $pageCount) $currentPage = $pageCount;

        $startPage = $currentPage - $n;
        $endPage = $currentPage + $n;

        if ($startPage < 1) {
            $endPage += (1 - $startPage);
            $startPage = 1;
        }

        if ($endPage > $pageCount) {
            $startPage -= ($endPage - $pageCount);
            $endPage = $pageCount;
        }

        if ($startPage < 1) $startPage = 1;

        if ($startPage > 1) {
            $result[] = new PageItem(1, $currentPage == 1);
            if ($startPage > 2) $result[] = new PageItem(0);
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            $result[] = new PageItem($i, $i == $currentPage);
        }

        if ($endPage < $pageCount) {
            if ($endPage < $pageCount - 1) $result[] = new PageItem(0);
            $result[] = new PageItem($pageCount, $currentPage == $pageCount);
        }

        return $result;
    }
}
?>