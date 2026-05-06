<?php
class PaginationSearchInput {
    private $page = 1;
    private $pageSize = 20;
    private $searchValue = '';
    private $maxPageSize = 100;

    public function getPage() {
        return $this->page;
    }

    public function setPage($value) {
        $this->page = $value < 1 ? 1 : $value;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

    public function setPageSize($value) {
        if ($value < 0) $this->pageSize = 0;
        else if ($value > $this->maxPageSize) $this->pageSize = $this->maxPageSize;
        else $this->pageSize = $value;
    }

    public function getSearchValue() {
        return $this->searchValue;
    }

    public function setSearchValue($value) {
        $this->searchValue = trim($value ?? '');
    }

    public function getOffset() {
        return $this->pageSize > 0 ? ($this->page - 1) * $this->pageSize : 0;
    }
}
?>