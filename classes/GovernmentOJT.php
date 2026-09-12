<?php

require_once 'Intern.php';

class GovernmentOJT extends Intern
{
    public function getOJTDescription(): string
    {
        return "Internship placement in a government organization.";
    }

    public function getRequirements(): array
    {
        return [
            "Endorsement Letter",
            "Memorandum of Agreement",
            "Medical Certificate",
            "OJT Training Plan"
        ];
    }
}