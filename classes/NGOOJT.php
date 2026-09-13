<?php

require_once 'Intern.php';

class NGOOJT extends Intern
{
    public function getOJTDescription(): string
    {
        return "Internship placement in a non-government organization.";
    }

    public function getRequirements(): array
    {
        return [
            "Endorsement Letter",
            "Memorandum of Agreement",
            "Medical Certificate",
            "Volunteer Agreement",
            "NGO Orientation Form"
        ];
    }

    public function getOJTTypeLabel(): string
    {
        return "NGO OJT";
    }
}
