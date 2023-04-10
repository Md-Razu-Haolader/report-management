<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use Illuminate\Contracts\Mail\Mailable as MailableContract;

interface MailerInterface
{
    public static function send(string $to, MailableContract $mailerClass);
}
