<?php

namespace Salt\Core\Services;

use MailchimpTransactional\ApiClient as Mandrill;

class MandrillService
{
    /**
     * Send a template mailer via the Mandrill service
     *
     * Docs - https://mandrillapp.com/api/docs/messages.JSON.html#method=send-template
     *
     * @param String $recipient_email the receipient email address
     * @param String $html_content The actual HTML email content
     * @param String $subject The email subject
     * @param String $from_email The from email
     * @param String $from_name The from name
     * @param String $reply_to_email The reply to email
     * @param String $metadata The email meta data
     * @param Array $merge_vars
     *
     * @return Object $result an object containing the result from Mandrill API
     */
    public static function sendTemplateMail($recipient_email, $html_content, $subject, $from_email, $from_name, $reply_to_email, $metadata, $merge_vars = null): Object
    {
        $key = config('core.mail.mandrill.key');
        $mandrill = new Mandrill();
        $mandrill->setApiKey($key);

        $template_name = config('core.mail.mandrill.template');
        $template_content = [
            [
                'name' => 'main',
                'content' => $html_content,
            ],
        ];

        $message = [
            'html' => $html_content,
            'subject' => $subject,
            'from_email' => $from_email,
            'from_name' => $from_name,
            'to' => [
                [
                    'email' => $recipient_email,
                ],
            ],
            'headers' => ['Reply-To' => $reply_to_email],
            'important' => false,
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => null,
            'auto_html' => null,
            'inline_css' => null,
            'url_strip_qs' => null,
            'preserve_recipients' => null,
            'view_content_link' => null,
            'tracking_domain' => null,
            'signing_domain' => null,
            'return_path_domain' => null,
            'metadata' => $metadata,
            'merge' => true,
            'merge_language' => 'mailchimp',
            'global_merge_vars' => $merge_vars,
        ];

        $async = false;

        $body = [
            'key' => $key,
            'template_name' => $template_name,
            'template_content' => $template_content,
            'message' => $message,
            'async' => $async,
        ];

        /**
         * PHPStan type error is coming from vendor package
         * 
         * @phpstan-ignore-next-line    
         */
        $result = $mandrill->messages->sendTemplate($body);

        return $result;
    }
}
