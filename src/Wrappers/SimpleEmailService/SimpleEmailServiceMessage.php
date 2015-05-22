<?php
Namespace Wrappers\Aws
{
    /**
     * SimpleEmailServiceMessage PHP class
     *
     * @package AmazonSimpleEmailService
     * @link https://github.com/ehime/Library-AWS-SES
     * @version 0.1
     */
    Final Class SimpleEmailServiceMessage Extends SimpleEmailServiceAbstract
    {
        // these are public for convenience only
        // these are not to be used outside of the SimpleEmailService class.
        public  $to                 = '',
                $cc                 = '',
                $bcc                = '',
                $replyTo            = '',
                $recipientCharset   = '';

        public  $from               = '', 
                $returnPath         = '';

        public  $subject            = '',
                $messageText        = '',
                $messageHtml        = '';

        public  $subjectCharset     = '',
                $messageTextCharset = '',
                $messageHtmlCharset = '';

        public  $attachments        = [],
                $customHeaders      = [];
    
        public function __construct()
        {
            $this->to                   = [];
            $this->cc                   = [];
            $this->bcc                  = [];
            $this->replyTo              = [];
            $this->recipientCharset     = 'UTF-8';
    
            $this->from                 = null;
            $this->returnPath           = null;
    
            $this->subject              = null;
            $this->messageText          = null;
            $this->messageHtml          = null;
    
            $this->subjectCharset       = 'UTF-8';
            $this->messageTextCharset   = 'UTF-8';
            $this->messageHtmlCharset   = 'UTF-8';
        }
    
        /**
         * addTo, addCC, addBCC, and addReplyTo have the following behavior:
         * If a single address is passed, it is appended to the current list of addresses.
         * If an array of addresses is passed, that array is merged into the current list.
         *
         * @param string $to
         * @return SimpleEmailServiceMessage $this
         */
        public function addTo($to)
        {
            if (! is_array($to))
            {
                $this->to[] = $to;
            }

            else
            {
                $this->to = array_unique(array_merge($this->to, $to));
            }
    
            return $this;
        }

        /**
         * Add carbon copy
         *
         * @param string $cc
         * @return SimpleEmailServiceMessage $this
         */
        public function addCC($cc)
        {
            if (! is_array($cc))
            {
                $this->cc[] = $cc;
            }

            else
            {
                $this->cc = array_merge($this->cc, $cc);
            }
    
            return $this;
        }

        /**
         * Add blind carbon copy
         *
         * @param string $bcc
         * @return SimpleEmailServiceMessage $this
         */
        public function addBCC($bcc)
        {
            if (! is_array($bcc))
            {
                $this->bcc[] = $bcc;
            }

            else
            {
                $this->bcc = array_merge($this->bcc, $bcc);
            }
    
            return $this;
        }

        /**
         * Add reply to
         *
         * @param string $replyTo
         * @return SimpleEmailServiceMessage $this
         */
        public function addReplyTo($replyTo)
        {
            if (! is_array($replyTo))
            {
                $this->replyTo[] = $replyTo;
            }

            else
            {
                $this->replyTo = array_merge($this->replyTo, $replyTo);
            }
    
            return $this;
        }

        /**
         * Set sender
         *
         * @param string $from
         * @return SimpleEmailServiceMessage $this
         */
        public function setFrom($from)
        {
            $this->from = $from;

            return $this;
        }

        /**
         * Set return path
         *
         * @param string $returnPath
         * @return SimpleEmailServiceMessage $this
         */
        public function setReturnPath($returnPath)
        {
            $this->returnPath = $returnPath;

            return $this;
        }

        /**
         * Sets recipients character set
         * 
         * @param string $charset
         * @return SimpleEmailServiceMessage $this
         */
        public function setRecipientsCharset($charset) 
        {
            $this->recipientCharset = $charset;
    
            return $this;
        }

        /**
         * Sets email subject
         * 
         * @param string $subject
         * @return SimpleEmailServiceMessage $this
         */
        public function setSubject($subject) 
        {
            $this->subject = $subject;
    
            return $this;
        }

        /**
         * Sets subjects character set
         * 
         * @param string $charset
         * @return SimpleEmailServiceMessage $this
         */
        public function setSubjectCharset($charset) 
        {
            $this->subjectCharset = $charset;
    
            return $this;
        }

        /**
         * Create message from string
         * 
         * @param string $text
         * @param null $html
         * @return SimpleEmailServiceMessage $this
         */
        public function setMessageFromString($text, $html = null) 
        {
            $this->messageText = $text;
            $this->messageHtml = $html;
    
            return $this;
        }

        /**
         * Create message from file
         * 
         * @param string $textFile
         * @param null $htmlFile
         * @return SimpleEmailServiceMessage $this
         */
        public function setMessageFromFile($textFile, $htmlFile = null) 
        {
            $this->messageText =  (file_exists($textFile) && is_file($textFile) && is_readable($textFile))
                ? file_get_contents($textFile)
                : null;

            $this->messageHtml = (file_exists($htmlFile) && is_file($htmlFile) && is_readable($htmlFile))
                ? file_get_contents($htmlFile)
                : null;

            return $this;
        }

        /**
         * Create message from uniform resource
         * 
         * @param string $textUrl
         * @param null   $htmlUrl
         * @return SimpleEmailServiceMessage $this
         */
        public function setMessageFromURL($textUrl, $htmlUrl = null) 
        {
            $this->messageText = (null !== $textUrl)
                ? file_get_contents($textUrl)
                : null;

            $this->messageHtml = (null !== $htmlUrl)
                ? file_get_contents($htmlUrl)
                : null;
    
            return $this;
        }

        /**
         * Set messages characacter set
         * 
         * @param string $textCharset
         * @param null   $htmlCharset
         * @return SimpleEmailServiceMessage $this
         */
        public function setMessageCharset($textCharset, $htmlCharset = null) 
        {
            $this->messageTextCharset = $textCharset;
            $this->messageHtmlCharset = $htmlCharset;
    
            return $this;
        }
    
        /**
         * Add custom header - this works only with SendRawEmail
         *
         * @param string $header Your custom header
         * @return SimpleEmailServiceMessage $this
         * @link( Restrictions on headers, http://docs.aws.amazon.com/ses/latest/DeveloperGuide/header-fields.html)
         */
        public function addCustomHeader($header) 
        {
            $this->customHeaders[] = $header;
    
            return $this;
        }
    
        /**
         * Add email attachment by directly passing the content
         *
         * @param string $name      The name of the file attachment AS it will appear in the email
         * @param string $data      The contents of the attachment file
         * @param string $mimeType  Specify custom MIME type
         * @param string $contentId Content ID of the attachment for inclusion in the mail message
         * @return SimpleEmailServiceMessage $this
         */
        public function addAttachmentFromData($name, $data, $mimeType = 'application/octet-stream', $contentId = null)
        {
            $this->attachments[$name] = [
                'name'      => $name,
                'mimeType'  => $mimeType,
                'data'      => $data,
                'contentId' => $contentId,
            ];
    
            return $this;
        }
    
        /**
         * Add email attachment by passing file path
         *
         * @param string $name      The name of the file attachment AS it will appear in the email
         * @param string $path      Path to the attachment file
         * @param string $mimeType  Specify custom MIME type
         * @param string $contentId Content ID of the attachment for inclusion in the mail message
         * @return  boolean Status of the operation
         */
        public function addAttachmentFromFile($name, $path, $mimeType = 'application/octet-stream', $contentId = null)
        {
            if (file_exists($path) && is_file($path) && is_readable($path))
            {
                $this->attachments[$name] = [
                    'name'      => $name,
                    'mimeType'  => $mimeType,
                    'data'      => file_get_contents($path),
                    'contentId' => $contentId,
                ];
                return true;
            }
            return false;
        }
    
        /**
         * Get the raw mail message
         *
         * @return string
         */
        public function getRawMessage()
        {
            $boundary = uniqid(rand(), true);

            $rawMessage = implode("\n", $this->customHeaders) . "\n";
            // $rawMessage .= 'List-Unsubscribe: <mailto:jd.daniel@mheducation.com>, <http://www.mheducation.com/?unsub=jd.daniel@mheducation.com>' . "\n";
            $rawMessage .= 'To: ' . $this->encodeRecipients($this->to) . "\n";
            $rawMessage .= 'From: ' . $this->encodeRecipients($this->from) . "\n";
    
            if (! empty($this->cc))
            {
                $rawMessage .= 'CC: ' . $this->encodeRecipients($this->cc) . "\n";
            }

            if (! empty($this->bcc))
            {
                $rawMessage .= 'BCC: ' . $this->encodeRecipients($this->bcc) . "\n";
            }

            if ($this->notEmpty($this->subject))
            {
                $rawMessage .= 'Subject: =?' . $this->subjectCharset . '?B?' . base64_encode($this->subject) . "?=\n";
            }
    
            $rawMessage .= 'MIME-Version: 1.0' . "\n";
            $rawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\n";
            $rawMessage .= "\n--{$boundary}\n";
            $rawMessage .= 'Content-type: Multipart/Alternative; boundary="alt-' . $boundary . '"' . "\n";
    
            if ($this->notEmpty($this->messageText))
            {
                $charset = empty($this->messageTextCharset) ? '' : "; charset=\"{$this->messageTextCharset}\"";
                $rawMessage .= "\n--alt-{$boundary}\n";
                $rawMessage .= 'Content-Type: text/plain' . $charset . "\n\n";
                $rawMessage .= $this->messageText . "\n";
            }
    
            if ($this->notEmpty($this->messageHtml))
            {
                $charset = empty($this->messageHtmlCharset) ? '' : "; charset=\"{$this->messageHtmlCharset}\"";
                $rawMessage .= "\n--alt-{$boundary}\n";
                $rawMessage .= 'Content-Type: text/html' . $charset . "\n\n";
                $rawMessage .= $this->messageHtml . "\n";
            }

            $rawMessage .= "\n--alt-{$boundary}--\n";
    
            foreach ($this->attachments AS $attachment)
            {
                $rawMessage .= "\n--{$boundary}\n";
                $rawMessage .= 'Content-Type: ' . $attachment['mimeType'] . '; name="' . $attachment['name'] . '"' . "\n";
                $rawMessage .= 'Content-Disposition: attachment' . "\n";

                if (! empty($attachment['contentId']))
                {
                    $rawMessage .= 'Content-ID: ' . $attachment['contentId'] . '' . "\n";
                }

                $rawMessage .= 'Content-Transfer-Encoding: base64' . "\n";
                $rawMessage .= "\n" . chunk_split(base64_encode($attachment['data']), 76, "\n") . "\n";
            }
    
            $rawMessage .= "\n--{$boundary}--\n";

            return base64_encode($rawMessage);
        }
    
        /**
         * Encode recipient with the specified charset in `recipientCharset`
         *
         * @param  string|array $recipient Single recipient or array of recipients
         * @return string            Encoded recipients joined with comma
         */
        public function encodeRecipients($recipient)
        {
            if (is_array($recipient)) return implode(', ', array_map([$this, 'encodeRecipients'], $recipient));

            if (preg_match("/(.*)<(.*)>/", $recipient, $regs))
            {
                $recipient = '=?' . $this->recipientCharset . '?B?'.base64_encode($regs[1]).'?= <'.$regs[2].'>';
            }
    
            return $recipient;
        }
    
        /**
         * Validates whether the message object has sufficient information to submit a request to SES.
         * This does not guarantee the message will arrive, nor that the request will succeed;
         * instead, it makes sure that no required fields are missing.
         *
         * This is used internally before attempting a SendEmail or SendRawEmail request,
         * but it can be used outside of this file if verification is desired.
         * May be useful if e.g. the data is being populated from a form; developers can generally
         * use this function to verify completeness instead of writing custom logic.
         *
         * @return boolean
         */
        public function validate()
        {
            if (0 == count($this->to)) return false;

            if (null == $this->from|| 0 == strlen($this->from)) return false;

            // messages require at least one of: subject, MessageText, MessageHtml.
            if ((null == $this->subject    || 0 == strlen($this->subject))
            && ( null ==$this->messageText || 0 == strlen($this->messageText))
            && ( null ==$this->messageHtml || 0 == strlen($this->messageHtml)))
            {
                return false;
            }
    
            return true;
        }
    }
}