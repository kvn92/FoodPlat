<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;


class ContactDTO
{
    private const MIN_LENGTH = 5;
    private const MAX_LENGTH = 200;
    private const MIN_MESSAGE = 'Vous devez entrer au moins {{ limit }} caractères.';
    private const MAX_MESSAGE = 'Vous ne pouvez pas dépasser {{ limit }} caractères.';


    #[NotBlank()]
    public string $name =''; 

    #[NotBlank()]
    #[Assert\Email()]
    public string $email = ''; 


    #[NotBlank]
    #[Assert\Length(
        min: self::MIN_LENGTH,
        max: self::MAX_LENGTH,
        minMessage: self::MIN_MESSAGE,
        maxMessage: self::MAX_MESSAGE
    )]
    public string $message = '';
}
