# Mail

## Mandrill

You need the following ENV variables in order for the Mandrill service to work:

```bash
MANDRILL_KEY
MANDRILL_TEMPLATE
```

To send a mail via Mandrill, you can use the `MandrillService::sendTemplateMail()` method:

```php
<?php
// Salt\Core\Services\MandrillService

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
```
