<?php


class user_log
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $mail;
    private string $date;

    public function __construct($id, $firstName, $lastName, $mail){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
    }

    public function GetId(): int
    {
        return $this->id;
    }
    public function GetFirstName(): string
    {
        return $this->firstName;
    }
    public function GetLastName(): string
    {
        return $this->lastName;
    }
    public function GetMail(): string
    {
        return $this->mail;
    }
    public function GetDate(): string
    {
        return $this->date;
    }
}