<?php

class Intern
{
    protected string $name;
    protected string $studentId;
    protected string $company;
    protected int $hoursRendered;
    protected int $requiredHours;

    /*
     * PRIVATE PROPERTY
     * Only this class manages the internal record ID.
     * Child classes and outside code never touch this directly,
     * which is what makes it "private" rather than "protected".
     */
    private string $recordId;

    public function __construct(
        string $name,
        string $studentId,
        string $company,
        int $hoursRendered,
        int $requiredHours
    ) {
        $this->validateHours($hoursRendered, $requiredHours);

        $this->name = $name;
        $this->studentId = $studentId;
        $this->company = $company;
        $this->hoursRendered = $hoursRendered;
        $this->requiredHours = $requiredHours;

        $this->recordId = $this->generateRecordId();
    }

    /*
     * PRIVATE METHOD
     * Internal validation logic that only the Intern class
     * itself is responsible for enforcing. Child classes do
     * not need to know how this works, and are not allowed
     * to override or bypass it.
     */
    private function validateHours(int $hoursRendered, int $requiredHours): void
    {
        if ($requiredHours <= 0) {
            throw new InvalidArgumentException(
                "Required hours must be greater than zero."
            );
        }

        if ($hoursRendered < 0) {
            throw new InvalidArgumentException(
                "Hours rendered cannot be negative."
            );
        }

        if ($hoursRendered > $requiredHours) {
            throw new InvalidArgumentException(
                "Hours rendered cannot be greater than required hours."
            );
        }
    }

    /*
     * PRIVATE METHOD
     * Generates an internal record identifier. This is an
     * implementation detail hidden from the rest of the
     * system; nothing outside this class needs to know how
     * IDs are generated.
     */
    private function generateRecordId(): string
    {
        return uniqid("intern_");
    }

    public function getRecordId(): string
    {
        return $this->recordId;
    }

    public function getProgress(): float
    {
        if ($this->requiredHours <= 0) {
            return 0;
        }

        return ($this->hoursRendered / $this->requiredHours) * 100;
    }

    /*
     * OVERRIDDEN METHOD #1 (in child classes)
     * Demonstrates polymorphism: same method name and
     * signature, different behavior per subclass.
     */
    public function getOJTDescription(): string
    {
        return "General OJT internship placement.";
    }

    /*
     * OVERRIDDEN METHOD #2 (in child classes)
     * Demonstrates polymorphism.
     */
    public function getRequirements(): array
    {
        return [];
    }

    /*
     * OVERRIDDEN METHOD #3 (in child classes)
     * A third polymorphic method, used to replace manual
     * get_class() string-checking in the UI. Calling this
     * method on different subclass objects produces a
     * different label automatically.
     */
    public function getOJTTypeLabel(): string
    {
        return "General OJT";
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
