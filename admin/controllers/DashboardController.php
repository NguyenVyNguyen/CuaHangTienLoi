<?php

require_once __DIR__ . "/../../app/services/ReportService.php";

class DashboardController
{
    private $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function index()
    {
        // Lấy dữ liệu dashboard giống ASP.NET GetDashboardDataAsync()
        $model = $this->reportService->getDashboardData();

        $title = "Dashboard - Tổng quan hệ thống";

        include __DIR__ . "/../views/dashboard/index.php";
    }
}