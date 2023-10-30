<?php

namespace App\Traits\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

trait HandlerMailsTrait
{
    /**
     * Dispatches an email job.
     *
     * @param ShouldQueue $job
     * @param string $method
     */
    public function dispatchEmailJob(ShouldQueue $job, string $method)
    {
        try {
            // Dispatch the provided email job
            dispatch($job);

            // Log the execution
            Log::channel('mailsSucess')->debug(json_encode([
                'status' => 'Success',
                'controller' => $method,
                'message' => 'Job dispatched successfully',
            ]));
        } catch (\Exception $e) {

            // Log the exception
            Log::channel('mailsFails')->error(json_encode([
                'status' => 'error',
                'controller' => $method,
                'message' => 'Error dispatching email job',
                'error' => $e->getMessage(),
            ]));
        }
    }

    /**
     * Sends an email.
     *
     * @param string $email
     * @param Mailable $mailable
     * @param string $method
     */
    public function sendEmail(string $email, Mailable $mailable, string $method)
    {
        try {

            // Send the email
            Mail::to($email)->queue($mailable);

            // Log the execution
            Log::channel('mailsSucess')->debug(json_encode([
                'status' => 'Success',
                'controller' => $method,
                'message' => 'Email sent successfully',
            ]));
        } catch (Exception $e) {

            // Log the exception
            Log::channel('mailsFails')->error(json_encode([
                'status' => 'error',
                'controller' => $method,
                'message' => 'Error dispatching email job',
                'error' => $e->getMessage(),
            ]));

            // Re-throw the exception
            throw $e;
        }
    }
}
