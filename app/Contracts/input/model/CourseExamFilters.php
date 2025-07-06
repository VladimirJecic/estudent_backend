<?php
namespace App\Contracts\input\model;
use Carbon\Carbon;

class CourseExamFilters
{
    public int $page;
    public int $pageSize;
    public ?string $courseName;

    public ?string $dateFrom;

    public ?string $dateTo;

    public function __construct(array $data)
    {
       $this->page = $data["page"] ?? 1;
       $this->pageSize = $data["page-size"] ?? 10;
       $this->courseName = $data["course-name"] ?? null;
       $this->dateFrom = isset($data['date-from']) ? Carbon::parse($data['date-from'])->toDateString() : null;
       $this->dateTo = isset($data['date-to']) ? Carbon::parse($data['date-to'])->toDateString() : null;
    }
}