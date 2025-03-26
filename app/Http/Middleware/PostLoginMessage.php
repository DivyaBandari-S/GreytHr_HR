<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostLoginMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('post_login')) {
            session()->forget('post_login');
            return $this->generatePostLoginResponse();
        }

        return $next($request);
    }

    protected function generatePostLoginResponse(): Response
    {
        try {
            $user = Auth::user();

            // Validate and format user names
            $firstName = $user->employee_name ?? 'User';
            // $lastName = $user->last_name ?? '';

            if (empty($firstName)) {
                throw new \Exception('User name is missing');
            }

            $userName = ucwords(trim($firstName));
            $homeUrl = route('home');

            $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Loading...</title>
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
                <style>
                    body, html {
                        height: 100%;
                        margin: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: #f8f9fa;
                        font-family: \'Montserrat\', sans-serif;
                    }
                    #message {
                        font-size: 1.25em;
                        color: #3b4452;
                        font-weight: 500;
                        text-align: center;
                    }
                    .name {
                        font-weight: bold;
                        color: #02114f;
                    }
                    .dots {
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .dots span {
                        display: inline-block;
                        width: 8px;
                        height: 8px;
                        margin-left: 2px;
                        background-color: #333;
                        border-radius: 50%;
                        opacity: 0;
                        animation: blink 1.4s infinite both;
                    }
                    .dots span:nth-child(1) {
                        animation-delay: 0.2s;
                    }
                    .dots span:nth-child(2) {
                        animation-delay: 0.4s;
                    }
                    .dots span:nth-child(3) {
                        animation-delay: 0.6s;
                    }
                    @keyframes blink {
                        0%, 80%, 100% { opacity: 0; }
                        40% { opacity: 1; }
                    }
                        
                    .loader-start {
                        scale: 3;
                        height: 50px;
                        width: 40px;
                    }
                    
                    .box-start {
                        position: relative;
                        opacity: 0;
                        left: 10px;
                    }
                    
                    .side-left {
                        position: absolute;
                        background-color: #286cb5;
                        width: 19px;
                        height: 5px;
                        transform: skew(0deg, -25deg);
                        top: 14px;
                        left: 10px;
                    }
                    
                    .side-right {
                        position: absolute;
                        background-color: #2f85e0;
                        width: 19px;
                        height: 5px;
                        transform: skew(0deg, 25deg);
                        top: 14px;
                        left: -9px;
                    }
                    
                    .side-top {
                        position: absolute;
                        background-color: #5fa8f5;
                        width: 20px;
                        height: 20px;
                        rotate: 45deg;
                        transform: skew(-20deg, -20deg);
                    }
                    
                    .box-1 {
                        animation: from-left 4s infinite;
                    }
                    
                    .box-2 {
                        animation: from-right 4s infinite;
                        animation-delay: 1s;
                    }
                    
                    .box-3 {
                        animation: from-left 4s infinite;
                        animation-delay: 2s;
                    }
                    
                    .box-4 {
                        animation: from-right 4s infinite;
                        animation-delay: 3s;
                    }
                    
                    @keyframes from-left {
                        0% {
                        z-index: 20;
                        opacity: 0;
                        translate: -20px -6px;
                        }
                    
                        20% {
                        z-index: 10;
                        opacity: 1;
                        translate: 0px 0px;
                        }
                    
                        40% {
                        z-index: 9;
                        translate: 0px 4px;
                        }
                    
                        60% {
                        z-index: 8;
                        translate: 0px 8px;
                        }
                    
                        80% {
                        z-index: 7;
                        opacity: 1;
                        translate: 0px 12px;
                        }
                    
                        100% {
                        z-index: 5;
                        translate: 0px 30px;
                        opacity: 0;
                        }
                    }
                    
                    @keyframes from-right {
                        0% {
                        z-index: 20;
                        opacity: 0;
                        translate: 20px -6px;
                        }
                    
                        20% {
                        z-index: 10;
                        opacity: 1;
                        translate: 0px 0px;
                        }
                    
                        40% {
                        z-index: 9;
                        translate: 0px 4px;
                        }
                    
                        60% {
                        z-index: 8;
                        translate: 0px 8px;
                        }
                    
                        80% {
                        z-index: 7;
                        opacity: 1;
                        translate: 0px 12px;
                        }
                    
                        100% {
                        z-index: 5;
                        translate: 0px 30px;
                        opacity: 0;
                        }
                    }
                </style>
                <script>
                    setTimeout(function() {
                        window.location.href = "' . $homeUrl . '";
                    }, 3000);
                </script>
            </head>
            <body>
                <div style="width: 100%">
                    <div style="width: 100%; text-align: center">
                        <img src="images/hr_new_blue.png" alt="Company Logo" style="width: 14em !important; margin-bottom: 30px">
                    </div>
                    <div id="message" style="width: 100%; margin-bottom: 30px">
                        Hi <span class="name">' . $userName . '</span>, just a moment, we are getting things ready for you
                    </div>
                    <div style="width: 100%; text-align: center">
                        <div class="loader-start" style="margin: auto; padding-top: 20px">
                            <div class="box-start box-1">
                                <div class="side-left"></div>
                                <div class="side-right"></div>
                                <div class="side-top"></div>
                            </div>
                            <div class="box-start box-2">
                                <div class="side-left"></div>
                                <div class="side-right"></div>
                                <div class="side-top"></div>
                            </div>
                            <div class="box-start box-3">
                                <div class="side-left"></div>
                                <div class="side-right"></div>
                                <div class="side-top"></div>
                            </div>
                            <div class="box-start box-4">
                                <div class="side-left"></div>
                                <div class="side-right"></div>
                                <div class="side-top"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
            return new Response($html, 200);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error and show a default message)
            $homeUrl = route('home');
            $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f8f9fa;
                font-family: \'Montserrat\', sans-serif;
            }
            #message {
                font-size: 24px;
                color: #333;
                text-align: center;
            }
            .error {
                font-weight: bold;
                color: #dc3545; /* Red color */
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = "' . $homeUrl . '";
            }, 3000);
        </script>
    </head>
    <body>
        <div id="message">
            <div class="error">An error occurred: ' . $e->getMessage() . '</div>
            <div>Please try again later.</div>
        </div>
    </body>
    </html>';



            return new Response($html, 500);
        }
    }
}
