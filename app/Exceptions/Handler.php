<?php

namespace App\Exceptions;

use Error;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use Psr\Log\LogLevel;
use Ramsey\Collection\Exception\ValueExtractionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    /**
     * A list of the expected exceptions.
     * Register all exceptions here
     * @var array<Exception, string>
     *
     * */
    protected array $exceptions = [
        NotFoundHttpException::class => [
            'class' => NotFoundHttpException::class,
            'message' => 'The object was not found! ',
            'code' => 404,
        ],

        FilesystemAdapter::class => [
            'class' => FilesystemAdapter::class,
            'message' => 'The file was not found',
            'code' => 512,
        ],

        FilesystemException::class => [
            'class' => FilesystemException::class,
            'message' => 'The file was not saved please try again later',
            'code' => 512,
        ],

        ValueExtractionException::class => [
            'class' => ValueExtractionException::class,
            'message' => 'The file was not saved please try again later',
            'code' => 512,
        ],

        UnauthorizedException::class => [
            'class' => UnauthorizedException::class,
            'message' => 'You are unauthorized for this action',
            'code' => 401,
        ],

        Error::class => [
            'class' => Error::class,
            'message' => 'Error occurred please try again later',
            'code' => 512,
        ],

        Exception::class => [
            'class' => Exception::class,
            'message' => 'An error occurred please refresh the page and try again later',
            'code' => 500,
        ],

    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $exception, $request) {

            if (!config('app.debug')) {
                if (!array_key_exists(get_class($exception), $this->exceptions)) {
                    return errorResponse('error, please try again later');
                }

                $exception = $this->exceptions[get_class($exception)];
                return errorResponse($exception['message']);


//                foreach ($this->exceptions as $currentException){
//
//                    if($exception instanceof NotFoundHttpException){
//                        return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500);
//                    }
//
//                    if($exception instanceof UnauthorizedException){
//                        return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500);
//                    }
//
//                    if($exception instanceof $currentException['class']){
//                        return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500 );
//                    }
//                }
            }
        });
    }
}
