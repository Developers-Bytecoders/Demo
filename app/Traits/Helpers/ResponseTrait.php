<?php

namespace App\Traits\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

trait ResponseTrait
{
    /**
     * Return an appropriate response.
     *
     * @param array $options
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function makeResponse(array $options = [])
    {
        // Get the options.
        $data = $options['data'] ?? null;
        $view = $options['view'] ?? null;
        $message = $options['message'] ?? '';
        $success = $options['success'] ?? true;
        $status = $this->makeStatusCode($options['status'] ?? null);
        $exception = $options['exception'] ?? null;
        $redirectRoute = $options['redirect'] ?? null;
        $channel = $this->makeLogChannel($options['type'] ?? null, $success); // Pass the type because will be usually the same as channel
        $type = $this->makeLogType($options['type'] ?? null, $success);

        // Log the message.
        Log::channel($channel)->$type($message . ' | ' . static::class . '::' . ((new Exception())->getTrace()[1]['function']), [
            'Status' => ($success ? 'Success' : 'Error'),
            'Data' => ($success ? $data : ($exception ? $exception->getMessage() : null)),
        ]);

        // Prepare the response data.
        $responseData = [
            'status' => $success ? 'success' : 'error',
            'code_status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        // Add the error to the data if an exception was passed.
        if (!$success && $exception) {
            $responseData['data'] = ['error' => $exception->getMessage()];
        }

        // If the request expects JSON, return a JSON response.
        if (request()->expectsJson()) {
            return response()->json($responseData, $status);
        }

        // Validating the existence and comparing the language of the url with the allowed languages
        $locale = request()->segment(1);
        $validLocales = ['es', 'en'];
        $locale = in_array($locale, $validLocales) ? $locale : app()->getLocale();

        // If not expecting JSON, return a redirect or a view.
        if ($success) {
            if ($view)
                return view($view, compact('data'))->with('success', $message);
            else
                return $redirectRoute
                    ? redirect()->route($redirectRoute, ['locale' => $locale])->with('success', $message)
                    : redirect()->back()->with('success', $message);
        } else {
            $errorMessage = !$exception ? $message : ($message . ': ' . $exception->getMessage());

            if ($view)
                return view($view, compact('data'))->withErrors($errorMessage);
            else
                return $redirectRoute
                    ? redirect()->route($redirectRoute, ['locale' => $locale])->withErrors($errorMessage)->withInput()
                    : redirect()->back()->withErrors($errorMessage)->withInput();
        }
    }

    /**
     * Validate and get the status code if not defined
     * 
     * @param string $statusCode Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     * 
     * @return string The status code
     */
    private function makeStatusCode(int $statusCode = null)
    {
        if ($statusCode !== null && !array_key_exists($statusCode, Response::$statusTexts)) // If the status code is provided but not valid
            throw new InvalidArgumentException('The status code [' . $statusCode . '] is not supported');

        return $statusCode ?? Response::HTTP_OK;
    }

    /**
     * Validate and get the log channel if not defined
     * 
     * @param string $logChannel Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     * 
     * @return string The log channel
     */
    private function makeLogChannel(string $logChannel = null, bool $success = true)
    {
        if ($logChannel !== null && !in_array($logChannel, array_keys(config('logging.channels')))) // If the log channel is provided but not valid
            throw new InvalidArgumentException('The log channel [' . $logChannel . '] is not supported');

        if ($logChannel !== null) // Valid log channel
            return $logChannel;
        else
            return $success ? 'info' : 'fatal';
    }

    /**
     * Validate and get the log type if not defined
     * 
     * @param string $logType Value to check. If null, will be set the default
     * @param bool $success If the operation was successful or not
     * 
     * @return string The log type
     */
    private function makeLogType(string $logType = null, bool $success = true)
    {
        if ($logType !== null && !in_array($logType, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'])) // If the log type is provided but not valid
            throw new InvalidArgumentException('The log type [' . $logType . '] is not supported');

        if ($logType !== null) // Valid log type
            return $logType;
        else
            return $success ? 'debug' : 'error';
    }
}
