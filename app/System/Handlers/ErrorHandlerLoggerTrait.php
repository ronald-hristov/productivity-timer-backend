<?php


namespace App\System\Handlers;


trait ErrorHandlerLoggerTrait
{
    /**
     * Write to the error log if $logErrors has been set to true
     *
     * @return void
     */
    protected function writeToErrorLog(): void
    {
        $renderer = $this->callableResolver->resolve($this->logErrorRenderer);
        $error = $renderer($this->exception, $this->logErrorDetails);
        if (!$this->displayErrorDetails) {
            $error .= "\nTips: To display error details in HTTP response ";
            $error .= 'set "displayErrorDetails" to true in the ErrorHandler constructor.';
        }
        $error .= ' ' . json_encode(['url' => (string) $this->request->getUri()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES);

        $this->logError($error);
    }
}