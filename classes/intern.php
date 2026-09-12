<?php

class Intern
{
    protected string $name;
    protected string $studentId;
    protected string $company;
    protected int $hoursRendered;
    protected int $requiredHours;

    public function __construct(
        string $name,
        string $studentId,
        string $company,
        int $hoursRendered,
        int $requiredHours
    ) {
        $this->name = $name;
        $this->studentId = $studentId;
        $this->company = $company;
        $this->hoursRendered = $hoursRendered;
        $this->requiredHours = $requiredHours;
    }

    public function getProgress(): float
    {
        if ($this->requiredHours <= 0) {
            return 0;
        }

        return ($this->hoursRendered / $this->requiredHours) * 100;
    }

    /*
     * This method will be overridden by the child classes.
     */
    public function getOJTDescription(): string
    {
        return "General OJT internship placement.";
    }

    /*
     * This method will also be overridden
     * by the child classes.
     */
    public function getRequirements(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStudentId(): string
    {
        return $this->studentId;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getHoursRendered(): int
    {
        return $this->hoursRendered;
    }

    public function getRequiredHours(): int
    {
        return $this->requiredHours;
    }
}