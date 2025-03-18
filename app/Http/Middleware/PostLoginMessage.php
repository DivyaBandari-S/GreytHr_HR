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
                    .pl {
                        width: 3em;
                        height: 3em;
                    }

                    .pl__ring {
                    animation: ringA 2s linear infinite;
                    }

                    .pl__ring--a {
                    stroke: #f42f25;
                    }

                    .pl__ring--b {
                    animation-name: ringB;
                    stroke: #f49725;
                    }

                    .pl__ring--c {
                    animation-name: ringC;
                    stroke: #255ff4;
                    }

                    .pl__ring--d {
                    animation-name: ringD;
                    stroke: #f42582;
                    }

                    /* Animations */
                    @keyframes ringA {
                    from, 4% {
                        stroke-dasharray: 0 660;
                        stroke-width: 20;
                        stroke-dashoffset: -330;
                    }

                    12% {
                        stroke-dasharray: 60 600;
                        stroke-width: 30;
                        stroke-dashoffset: -335;
                    }

                    32% {
                        stroke-dasharray: 60 600;
                        stroke-width: 30;
                        stroke-dashoffset: -595;
                    }

                    40%, 54% {
                        stroke-dasharray: 0 660;
                        stroke-width: 20;
                        stroke-dashoffset: -660;
                    }

                    62% {
                        stroke-dasharray: 60 600;
                        stroke-width: 30;
                        stroke-dashoffset: -665;
                    }

                    82% {
                        stroke-dasharray: 60 600;
                        stroke-width: 30;
                        stroke-dashoffset: -925;
                    }

                    90%, to {
                        stroke-dasharray: 0 660;
                        stroke-width: 20;
                        stroke-dashoffset: -990;
                    }
                    }

                    @keyframes ringB {
                    from, 12% {
                        stroke-dasharray: 0 220;
                        stroke-width: 20;
                        stroke-dashoffset: -110;
                    }

                    20% {
                        stroke-dasharray: 20 200;
                        stroke-width: 30;
                        stroke-dashoffset: -115;
                    }

                    40% {
                        stroke-dasharray: 20 200;
                        stroke-width: 30;
                        stroke-dashoffset: -195;
                    }

                    48%, 62% {
                        stroke-dasharray: 0 220;
                        stroke-width: 20;
                        stroke-dashoffset: -220;
                    }

                    70% {
                        stroke-dasharray: 20 200;
                        stroke-width: 30;
                        stroke-dashoffset: -225;
                    }

                    90% {
                        stroke-dasharray: 20 200;
                        stroke-width: 30;
                        stroke-dashoffset: -305;
                    }

                    98%, to {
                        stroke-dasharray: 0 220;
                        stroke-width: 20;
                        stroke-dashoffset: -330;
                    }
                    }

                    @keyframes ringC {
                    from {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: 0;
                    }

                    8% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -5;
                    }

                    28% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -175;
                    }

                    36%, 58% {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: -220;
                    }

                    66% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -225;
                    }

                    86% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -395;
                    }

                    94%, to {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: -440;
                    }
                    }

                    @keyframes ringD {
                    from, 8% {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: 0;
                    }

                    16% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -5;
                    }

                    36% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -175;
                    }

                    44%, 50% {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: -220;
                    }

                    58% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -225;
                    }

                    78% {
                        stroke-dasharray: 40 400;
                        stroke-width: 30;
                        stroke-dashoffset: -395;
                    }

                    86%, to {
                        stroke-dasharray: 0 440;
                        stroke-width: 20;
                        stroke-dashoffset: -440;
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
                        <svg class="pl" width="240" height="240" viewBox="0 0 240 240">
                            <circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                            <circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
                        </svg>
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
