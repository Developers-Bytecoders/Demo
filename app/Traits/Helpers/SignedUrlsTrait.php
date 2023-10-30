<?php

namespace App\Traits\Helpers;

use Illuminate\Support\Facades\URL;

trait SignedUrlsTrait
{
    /**
     * Generate a temporary signed URL.
     *
     * @param string $routeName The name of the route that will handle the request.
     * @param int $expiration The number of minutes until the URL expires.
     * @param array $additionalParameters The additional parameters that will be included in the URL.
     * @return string
     */
    public function generateSignedUrl(string $routeName, int $expiration, array $additionalParameters = []): string
    {
        $defaultParameters = [
            'locale' => app()->getLocale(),
        ];

        $parameters = array_merge($defaultParameters, $additionalParameters);

        // Generate a temporary signed URL
        $signedUrl = URL::temporarySignedRoute(
            $routeName,
            now()->addMinutes($expiration),
            $parameters
        );

        return $signedUrl;
    }
}
