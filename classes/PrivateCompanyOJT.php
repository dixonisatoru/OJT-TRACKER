<?php

require_once 'Intern.php';

class PrivateCompanyOJT extends Intern
{
    public function getOJTDescription(): string
    {
        return "Internship placement in a private-sector company.";
    }

    public function getRequirements(): array
    {
        return [
            "Resume",
            "Endorsement Letter",
            "Memorandum of Agreement",
            "Medical Certificate",
            "Company Orientation Form"
        ];
    }
}