<?php

namespace App\Service;

interface EmailUtilInterface
{
    /**
     * Send email with CSV file, XML etc.
     *
     * @param string $recipient
     * @param array $attachments
     */
    function sendEmail(string $recipient, array $attachments): void;
}