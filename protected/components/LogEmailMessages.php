<?php

class LogEmailMessages extends CEmailLogRoute {

    /**
     * Sends log messages to specified email addresses.
     * @param array list of log messages
     */
    protected function processLogs($logs) {
        $message = '';
        foreach ($logs as $log) {
            $message.=$this->formatLogMessage($log[0], $log[1], $log[2], $log[3]);
            $message .= $this->moreInfo();
        }

        $message = wordwrap($message, 70);

        foreach ($this->getEmails() as $email) {
            $this->sendEmail($email, $this->getSubject(), $message);
        }
    }

    /**
     * More info about the error 
     *
     */
    protected function moreInfo() {
        $info = '';
        $info .= "Server: \n" . print_r($_SERVER, true) . "\n\n";
        $info .= "Get: \n" . print_r($_GET, true) . "\n\n";
        $info .= "Post: \n" . print_r($_POST, true) . "\n\n";

        $info .= "Referer: \n" . Yii::app()->request->getUrlReferrer() . "\n\n";

        $info .= "User Id: \n" . Yii::app()->user->id;

        return $info;
    }

}

