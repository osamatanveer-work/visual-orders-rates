<?php

namespace App\Libraries;

use App\Mail\ApiErrorMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class SendApiError {
    /**
     * sendErrorEmail
     * This method is responsible for sending an error email. It is INTENTIONAL
     * that the email addresses are not in a database (or other file). This
     * is to prevent additional exceptions being raised if the problem was a
     * connection to the database.
     *
     * @param string $message
     * @return void
     */
    public static function sendErrorEmail(string $message): void {
        $emailAddresses = [
            #'william.knauss@kingdom.com', //William Knauss - Kingdom IT
            #'tim.neal@kingdom.com', //Tim Neal - Klear/IPAS
            #'tim.neal@iprintandship.com', //Tim Neal - Klear/IPAS
            #'rachael@iprintandship.com', //Rachael Berguson - Klear/IPAS
            #'rachael@kleardigital.com', //Rachael Berguson - Klear/IPAS
            #'5703371887@vtext.com', //William Knauss
            #'5706622511@vtext.com', //Rachael Berguson
            #'5707870525@vtext.com' //Tim Neal
        ];

        //if the container does not have an apiRequestHeader object
        if (!App::has('apiRequestHeader')) {
            //default requestID to "UNKNOWN"
            $requestID = 'UNKNOWN';
        } else {
            //get the ID
            $requestID = App::make('apiRequestHeader')->id;
        }

        //iterate the emailAddresses
        foreach ($emailAddresses as $emailAddress) {
            //Queue an email to be sent
            Mail::to($emailAddress)->queue(new ApiErrorMail(['error' => $message, 'requestID' => $requestID]));
        }
    }
}
