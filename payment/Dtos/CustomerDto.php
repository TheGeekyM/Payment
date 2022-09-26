<?php

namespace Payment\Dtos;

class CustomerDto
{
    private string $name;
    private string $email;
    private string $ip;
    private string $language;
    private int $id;
    private string $phone;

    public function __construct(int $id, string $name, string $email, string $ip, string $language, string $phone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->ip = $ip;
        $this->language = $language;
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
