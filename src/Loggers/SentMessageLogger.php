<?php

namespace Teamnovu\LaravelNotificationLog\Loggers;

use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mime\Part\DataPart;
use Teamnovu\LaravelNotificationLog\Models\SentMessageLog;

class SentMessageLogger
{
    public function logSentMessage(MessageSent $event)
    {
        $body = $event->message->getBody();
        $message = SentMessageLog::updateOrCreate([
            'message_id' => $event->sent->getMessageId(),
        ],
            [

                'mailable' => $this->getMailable($event),
                'queued' => $this->getQueuedStatus($event),
                'to' => $this->listAddresses($event->message->getTo()),
                'cc' => $this->listAddresses($event->message->getCc()),
                'bcc' => $this->listAddresses($event->message->getBcc()),
                'reply_to' => $this->listAddresses($event->message->getReplyTo()),
                'sender' => $this->listAddresses([$event->message->getSender()]),
                'headers' => $this->convertHeaders($event->message->getHeaders()),
                'subject' => $event->message->getSubject(),
                'body' => $body instanceof AbstractPart ? ($event->message->getHtmlBody() ?? $event->message->getTextBody()) : $body,
                'sent_at' => $event->message->getDate(),
                'attachments' => $this->listAttachments($event->message->getAttachments()),
            ]);

        return $message;
    }

    /**
     * @param  Address[]  $addresses
     * @return string[]
     */
    protected function listAddresses(?array $addresses): array
    {
        return collect($addresses)
            ->filter()
            ->map(function (Address $address) {
                return $address->getName() ? "{$address->getName()} <{$address->getAddress()}>" : $address->getAddress();
            })->values()
            ->toArray();
    }

    protected function convertHeaders(Headers $getHeaders): array
    {
        $headers = [];
        foreach ($getHeaders->all() as $header) {
            $headers[$header->getName()] = $header->getBodyAsString();
        }

        return $headers;
    }

    /**
     * @param  DataPart[]  $getAttachments
     * @return array
     */
    protected function listAttachments(array $getAttachments): array
    {
        return collect($getAttachments)
            ->map(function (DataPart $attachment) {
                return $attachment->getFilename();
            })
            ->toArray();
    }

    /**
     * Get the name of the mailable.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     */
    protected function getMailable(MessageSent $event): array
    {
        if (isset($event->data['__laravel_notification'])) {
            return [$event->data['__laravel_notification'], $event->data['__laravel_notification_id']];
        }

        return [$event->data['__laravel_notification_log_mailable'] ?? ''];
    }

    /**
     * Determine whether the mailable was queued.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @return bool
     */
    protected function getQueuedStatus(MessageSent $event)
    {
        if (isset($event->data['__laravel_notification_queued'])) {
            return $event->data['__laravel_notification_queued'];
        }

        return $event->data['__laravel_notification_log_queued'] ?? false;
    }
}
