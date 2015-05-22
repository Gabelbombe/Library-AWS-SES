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
    Abstract Class SimpleEmailServiceAbstract
    {

        /**
         * Asserts empty and nullS
         *
         * @param $item
         * @return bool
         */
        protected function notEmpty($item)
        {
            return (null != $item && 0 < strlen($item)); // empty()
        }
    }
}