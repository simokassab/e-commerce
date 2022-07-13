<?php

namespace App\Exceptions;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \Exception;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use Ramsey\Collection\Exception\ValueExtractionException;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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
    protected array $exceptions= [
        [
            'class' => NotFoundHttpException::class,
            'message' => 'The object was not found! '
        ],
        [
            'class' => FilesystemAdapter::class,
            'message' => 'The file was not found'
        ],
        [
            'class' => \Illuminate\Filesystem\FilesystemAdapter::class,
            'message' => 'The file was not found'
        ],
        [
            'class' => FilesystemException::class,
            'message' => 'The file was not saved please try again later'
        ],
        [
            'class' => ValueExtractionException::class,
            'message' => 'The file was not saved please try again later'
        ],

    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {

        $this->exceptionsp[]=[
            'name' => \Exception::class,
            'message' => 'An error occurred please refresh the page and try again later'
        ];

        $this->renderable(function (Throwable $exception,$request) {



            if(!config('app.debug')){
                foreach ($this->exceptions as $currentException){

                    if($exception instanceof NotFoundHttpException){
                        return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500);
                    }

                    if($exception instanceof $currentException['class']){
                        return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500 );
                    }
                }
            }

            if(!config('app.debug_code')){
                if($exception instanceof \Error){
                    return errorResponse($currentException['message'] ?? 'error, please try again later' , [] , -1,500);
                }
            }
        });
    }
}
