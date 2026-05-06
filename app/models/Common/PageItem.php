<?php
class PageItem {
    public $Page;
    public $IsCurrent;

    public function __construct($pageNumber, $isCurrent = false) {
        $this->Page = $pageNumber;
        $this->IsCurrent = $isCurrent;
    }

    public function isEllipsis() {
        return $this->Page == 0;
    }
}
?>