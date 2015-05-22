<?php
Namespace Wrappers\SimpleEmailService
{
    /**
     * SimpleEmailService is based on Donovan Schonknecht's Amazon S3 PHP class, found here:
     * http://undesigned.org.za/2007/10/22/amazon-s3-php-class
     *
     * @copyright 2015 Jd Daniel
     */

    /**
     * SimpleEmailService PHP class
     *
     * @package AmazonSimpleEmailService
     * @link https://github.com/ehime/Library-AWS-SES
     * @version 0.1
     */
    Class SimpleEmailService Extends SimpleEmailServiceAbstract
    {
        protected   $accessKey, // AWS Access key
                    $secretKey, // AWS Secret key
                    $host;

        protected   $verifyHost = true;
        protected   $verifyPeer = true;

        /**
         * @return mixed|string
         */
        public function getAccessKey()
        {
            return $this->accessKey;
        }

        /**
         * @return mixed|string
         */
        public function getSecretKey()
        {
            return $this->secretKey;
        }

        /**
         * @return mixed|string
         */
        public function getHost()
        {
            return $this->host;
        }

        /**
         * verifyHost and verifyPeer determine whether curl verifies ssl certificates.
         * It may be necessary to disable these checks on certain systems. These only
         * have an effect if SSL is enabled.
         *
         * @return bool
         */
        public function verifyHost()
        {
            return $this->verifyHost;
        }

        /**
         * @param bool $enable
         */
        public function enableVerifyHost($enable = true)
        {
            $this->verifyHost = $enable;
        }

        /**
         * @return bool
         */
        public function verifyPeer()
        {
            return $this->verifyPeer;
        }

        /**
         * @param bool $enable
         */
        public function enableVerifyPeer($enable = true)
        {
            $this->verifyPeer = $enable;
        }

        /**
         * Constructor
         *
         * @param string $accessKey Access key
         * @param string $secretKey Secret key
         * @param string $host Amazon Host through which to send the emails
         * @return void
         */
        public function __construct($accessKey = null, $secretKey = null, $host = 'email.us-east-1.amazonaws.com')
        {
            if (null !== ($accessKey || $secretKey))
            {
                $this->setAuth($accessKey, $secretKey);
            }

            $this->host = $host;
        }

        /**
         * Set AWS access key and secret key
         *
         * @param string $accessKey Access key
         * @param string $secretKey Secret key
         * @return SimpleEmailService $this
         */
        public function setAuth($accessKey, $secretKey)
        {
            $this->accessKey = $accessKey;
            $this->secretKey = $secretKey;

            return $this;
        }

        /**
         * Lists the email addresses that have been verified and can be used AS the 'From' address
         *
         * @return array|bool An array containing two items: a list of verified email addresses, and the request id.
         */
        public function listVerifiedEmailAddresses()
        {
            $rest = New SimpleEmailServiceRequest($this, 'GET');
            $rest->setParameter('Action', 'ListVerifiedEmailAddresses');

            $rest = $rest->getResponse();
            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = [
                    'code'      => $rest->code, 
                    'message'   => 'Unexpected HTTP status'
                ];
            }

            if (false !== $rest->error)
            {
                $this->triggerError('listVerifiedEmailAddresses', $rest->error);
                return false;
            }

            $response = [];

            if (! isset($rest->body)) return $response;

            $addresses = [];

             foreach ($rest->body->ListVerifiedEmailAddressesResult->VerifiedEmailAddresses->member AS $address) 
            {
                $addresses[] = (string)$address;
            }

            $response['Addresses'] = $addresses;
            $response['RequestId'] = (string)$rest->body->ResponseMetadata->RequestId;

            return $response;
        }

        /**
         * Requests verification of the provided email address, so it can be used
         * AS the 'From' address when sending emails through SimpleEmailService.
         *
         * After submitting this request, you should receive a verification email
         * from Amazon at the specified address containing instructions to follow.
         *
         * @param string $email The email address to get verified
         * @return array|bool The request id for this request.
         */
        public function verifyEmailAddress($email)
        {
            $rest = New SimpleEmailServiceRequest($this, 'POST');
            $rest->setParameter('Action', 'VerifyEmailAddress');
            $rest->setParameter('EmailAddress', $email);

            $rest = $rest->getResponse();

            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = [
                    'code'      => $rest->code, 
                    'message'   => 'Unexpected HTTP status'
                ];
            }
            
            if (false !== $rest->error) 
            {
                $this->triggerError('verifyEmailAddress', $rest->error);
                return false;
            }

            $response['RequestId'] = (string) $rest->body->ResponseMetadata->RequestId;
            return $response;
        }

        /**
         * Removes the specified email address from the list of verified addresses.
         *
         * @param string $email The email address to remove
         * @return array|bool The request id for this request.
         */
        public function deleteVerifiedEmailAddress($email) 
        {
            $rest = New SimpleEmailServiceRequest($this, 'DELETE');
            $rest->setParameter('Action', 'DeleteVerifiedEmailAddress');
            $rest->setParameter('EmailAddress', $email);

            $rest = $rest->getResponse();
            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
            }

            if (false !== $rest->error)
            {
                $this->triggerError('DeleteVerifiedEmailAddress', $rest->error);
                return false;
            }

            $response['RequestId'] = (string) $rest->body->ResponseMetadata->RequestId;
            return $response;
        }

        /**
         * Retrieves information on the current activity limits for this account.
         * See http://docs.amazonwebservices.com/ses/latest/APIReference/API_GetSendQuota.html
         *
         * @return array|bool An array containing information on this account's activity limits.
         */
        public function getSendQuota()
        {
            $rest = New SimpleEmailServiceRequest($this, 'GET');
            $rest->setParameter('Action', 'GetSendQuota');

            $rest = $rest->getResponse();
            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = [
                    'code'      => $rest->code,
                    'message'   => 'Unexpected HTTP status'
                ];
            }

            if (false !== $rest->error)
            {
                $this->triggerError('getSendQuota', $rest->error);
                return false;
            }

            return (isset($rest->body))
                ? [
                    'Max24HourSend'     => (string) $rest->body->GetSendQuotaResult->Max24HourSend,
                    'MaxSendRate'       => (string) $rest->body->GetSendQuotaResult->MaxSendRate,
                    'SentLast24Hours'   => (string) $rest->body->GetSendQuotaResult->SentLast24Hours,
                    'RequestId'         => (string) $rest->body->ResponseMetadata->RequestId,
                  ]
                : [];
        }

        /**
         * Retrieves statistics for the last two weeks of activity on this account.
         * See http://docs.amazonwebservices.com/ses/latest/APIReference/API_GetSendStatistics.html
         *
         * @return array An array of activity statistics.  Each array item covers a 15-minute period.
         */
        public function getSendStatistics()
        {
            $rest = New SimpleEmailServiceRequest($this, 'GET');
            $rest->setParameter('Action', 'GetSendStatistics');

            $rest = $rest->getResponse();
            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
            }

            if (false !== $rest->error)
            {
                $this->triggerError('getSendStatistics', $rest->error);
                return false;
            }

            $response = [];
            if (! isset($rest->body)) return $response;

             $dataPoints = [];
             foreach ($rest->body->GetSendStatisticsResult->SendDataPoints->member AS  $dataPoint) 
             {
                $dataPoints[] = [
                    'Bounces'           => (string) $dataPoint->Bounces,
                    'Complaints'        => (string) $dataPoint->Complaints,
                    'DeliveryAttempts'  => (string) $dataPoint->DeliveryAttempts,
                    'Rejects'           => (string) $dataPoint->Rejects,
                    'Timestamp'         => (string) $dataPoint->Timestamp,
                ];
            }

            $response['SendDataPoints'] =  $dataPoints;
            $response['RequestId']      = (string) $rest->body->ResponseMetadata->RequestId;

            return $response;
        }


        /**
         * Given a SimpleEmailServiceMessage object, submits the message to the service for sending.
         *
         * @param SimpleEmailServiceMessage $sesMessage An instance of the message class
         * @param boolean $use_raw_request If this is true or there are attachments to the email `SendRawEmail` call will be used
         * @return array An array containing the unique identifier for this message and a separate request id.
         *         Returns false if the provided message is missing any required fields.
         */
        public function sendEmail($sesMessage, $use_raw_request = false)
        {
            if (! $sesMessage->validate())
            {
                $this->triggerError('sendEmail', 'Message failed validation.');
                return false;
            }

            $rest   = New SimpleEmailServiceRequest($this, 'POST');
            $action = (! empty($sesMessage->attachments) || $use_raw_request)
                ? 'SendRawEmail'
                : 'SendEmail';

            $rest->setParameter('Action', $action);

            if ($action == 'SendRawEmail')
            {
                $rest->setParameter('RawMessage.Data', $sesMessage->getRawMessage());
            }

            else
            {
                $i = 1;
                 foreach ($sesMessage->to AS $to)
                 {
                    $rest->setParameter('Destination.ToAddresses.member.'.$i, $sesMessage->encodeRecipients($to));
                    $i++;
                }

                if (is_array($sesMessage->cc))
                {
                    $i = 1;
                     foreach ($sesMessage->cc AS $cc)
                     {
                        $rest->setParameter('Destination.CcAddresses.member.'.$i, $sesMessage->encodeRecipients($cc));
                        $i++;
                    }
                }

                if (is_array($sesMessage->bcc))
                {
                    $i = 1;
                     foreach ($sesMessage->bcc AS $bcc)
                     {
                        $rest->setParameter('Destination.BccAddresses.member.'.$i, $sesMessage->encodeRecipients($bcc));
                        $i++;
                    }
                }

                if (is_array($sesMessage->replyTo))
                {
                    $i = 1;
                     foreach ($sesMessage->replyTo AS $replyTo)
                     {
                        $rest->setParameter('ReplyToAddresses.member.'.$i, $sesMessage->encodeRecipients($replyTo));
                        $i++;
                    }
                }

                $rest->setParameter('Source', $sesMessage->encodeRecipients($sesMessage->from));

                if (null != $sesMessage->returnPath)
                {
                    $rest->setParameter('ReturnPath', $sesMessage->returnPath);
                }

                if ($this->notEmpty($sesMessage->subject))
                {
                    $rest->setParameter('Message.Subject.Data', $sesMessage->subject);
                    if (null != $sesMessage->subjectCharset&& 0 < strlen($sesMessage->subjectCharset)) 
                    {
                        $rest->setParameter('Message.Subject.Charset', $sesMessage->subjectCharset);
                    }
                }

                if ($this->notEmpty($sesMessage->messageText))
                {
                    $rest->setParameter('Message.Body.Text.Data', $sesMessage->messageText);
                    if ($this->notEmpty($sesMessage->messageTextCharset))
                    {
                        $rest->setParameter('Message.Body.Text.Charset', $sesMessage->messageTextCharset);
                    }
                }

                if ($this->notEmpty($sesMessage->messageHtml))
                {
                    $rest->setParameter('Message.Body.Html.Data', $sesMessage->messageHtml);
                    if ($this->notEmpty($sesMessage->messageHtmlCharset))
                    {
                        $rest->setParameter('Message.Body.Html.Charset', $sesMessage->messageHtmlCharset);
                    }
                }
            }

            $rest = $rest->getResponse();
            if (false === $rest->error && 200 !== $rest->code)
            {
                $rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
            }

            if (false !== $rest->error)
            {
                $this->triggerError('sendEmail', $rest->error);
                return false;
            }

            $response['MessageId'] = (string) $rest->body->SendEmailResult->MessageId;
            $response['RequestId'] = (string) $rest->body->ResponseMetadata->RequestId;
            return $response;
        }

        /**
         * Trigger an error message
         *
         * {@internal Used by member functions to output errors}
         * @param  string $method The name of the function that failed
         * @param array $error Array containing error information
         * @return  void
         */
        public function triggerError($method, $error)
        {
            if (! $error)
            {
                trigger_error(sprintf("SimpleEmailService::%s(): Encountered an error, but no description given", $method), E_USER_WARNING);
            }

            else if (isset($error['curl']) && $error['curl'])
            {
                trigger_error(sprintf("SimpleEmailService::%s(): %s %s", $method, $error['code'], $error['message']), E_USER_WARNING);
            }

            else if (isset($error['Error']))
            {
                $e = $error['Error'];
                $message = sprintf("SimpleEmailService::%s(): %s - %s: %s\nRequest Id: %s\n", $method, $e['Type'], $e['Code'], $e['Message'], $error['RequestId']);
                trigger_error($message, E_USER_WARNING);
            }

            else
            {
                trigger_error(sprintf("SimpleEmailService::%s(): Encountered an error: %s", $method, $error), E_USER_WARNING);
            }
        }
    }
}