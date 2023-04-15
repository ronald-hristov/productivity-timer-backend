<?php declare(strict_types=1);


namespace App\Service;


class Auth
{
    protected ?array $currentUser;

    /**
     * Auth constructor.
     * @param array|null $currentUser
     */
    public function __construct(?array $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @return array|null
     */
    public function getCurrentUser(): ?array
    {
        return $this->currentUser;
    }
}