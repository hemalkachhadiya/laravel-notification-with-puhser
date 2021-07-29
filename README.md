# Laravel Notification Using Pusher
Laravel notification using pusher
## Demo setup steps
### Versions
```base
php version - "^7.3|^8.0"
laravel version - "^8.40"
```
### Clone
```base
git clone git@github.com:hemalkachhadiya/laravel-notification-with-puhser.git
```
### Install composer
Go to the project directory   
install composer using cmd
```composer
composer install
```
### setup .env file
copy ```.env.example``` and paste in project and rename to ```.env```   
```php
APP_URL=
```
set pusher credentials. you get this credentials from [pusher.com](https://pusher.com/tutorials/web-notifications-laravel-pusher-channels)
```base
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
```
after env setup run below commands for database
```base
php artisan key:generate
php artisan optimize:clear
```
and run project

## Create demo your self using following instructions
reference link : [pusher.com](https://pusher.com/tutorials/web-notifications-laravel-pusher-channels)

### Setting up your Pusher application
Create a [Pusher account](https://pusher.com/), if you have not already, and then set up your application.

### Setting up your Laravel application
You can create a new Laravel application by running the command below in your cmd:
```base
composer create-project laravel/laravel project-name
```
After that, we will need to install the Pusher PHP SDK, you can do this using Composer by running the command below:
```base
composer require pusher/pusher-php-server
```
When Composer is done, we will need to configure Laravel to use Pusher as its broadcast driver, to do this, open the .env file that is in the root directory of your Laravel installation. Update the values to correspond with the configuration below:
```base
BROADCAST_DRIVER=pusher

// Get the credentials from your pusher dashboard
PUSHER_APP_ID=XXXXX
PUSHER_APP_KEY=XXXXXXX
PUSHER_APP_SECRET=XXXXXXX
PUSHER_APP_CLUSTER=
```
Important Note: If you’re using the EU or AP Cluster, make sure to update the options array in your ```config/broadcasting.php``` config since Laravel defaults to using the US Server. You can use all the options the Pusher PHP Library supports.   
Open ```config/app.php``` and uncomment the ```App\Providers\BroadcastServiceProvider::class``` .     

### Create event
First we would create an Event class that would broadcast to Pusher from our Laravel application. Events can be fired from anywhere in the application.
```base
php artisan make:event SenMessage
```
This will create a new ```SenMessage``` class in the ```app/Events``` directory. Open the contents of the file and update to the following below:
```base
<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SenMessage implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;

    public $message;
    /**
    * Create a new event instance.
    *
    * @return void
    */

    public function __construct( $username, $message ) {
        $this->username = $username;
        $this->message = $message;
    }

    /**
    * Get the channels the event should broadcast on.
    *
    * @return \Illuminate\Broadcasting\Channel|array
    */

    public function broadcastOn() {
        return ['message-sended'];
        // return new PrivateChannel( 'channel-name' );
    }

    public function broadcastAs() {
        return 'message-send-event';
    }
}
```

### Creating the notification received views
Open the ```welcome.blade.php``` file and replace it with the HTML below.
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .notification-number {
            right: 34px;
            top: 9px;
            z-index: 1;
            border: 3px solid #FFF;
            border-radius: 50%;
            padding-top: 5px;
            height: 25px;
            width: 25px;
            font-family: sans-serif;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            line-height: 10px;
            color: #FFF;
            -webkit-animation: bounce 1s infinite;
        }

    </style>
</head>

<body class="antialiased bg-gray-900">
    <nav class="bg-gray-800">
        {{-- Web View --}}
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="block lg:hidden h-8 w-auto"
                            src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">
                        <img class="hidden lg:block h-8 w-auto"
                            src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg"
                            alt="Workflow">
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="#" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium"
                                aria-current="page">Dashboard</a>
                            <a href="#"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Team</a>
                            <a href="#"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                            <a href="#"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Calendar</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <div>
                        <button onclick="showDiv(this)" data-target="notification-dev"
                            class="bg-gray-800 p-1 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white border-2 text-white">
                            <span class="hidden notification-number absolute bg-red-500 ">0</span>
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        <div id="notification-dev"
                            class="hidden origin-top-right w-60 md:w-2/5 absolute right-10 mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <ol id="notitfications">
                                <li class="">
                                    <p class="p-2">No notification available.</p>
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="ml-3 relative">
                        <div>
                            <button type="button"
                                class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script type="text/javascript">
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var notificationNumber = parseInt($('.notification-number').text());

        // Initiate the Pusher JS library
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        // Subscribe to the channel we specified in our Laravel Event
        var channel = pusher.subscribe('message-sended');

        // Bind a function to a Event (the full Laravel class)
        channel.bind('message-send-event', function(data) {
            // this is called when the event notification is received...
            notificationNumber = notificationNumber + 1;
            var html = '<li class="grid grid-cols-1 grid-cols-4 p-3 border-b">' +
                '<div class="col-span-1 ">' +
                '<img class="inline-block h-16 w-16 rounded-lg" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80">' +
                '</div>' +
                '<div class="col-span-3">' +
                '<h3 class="font-bold">' + data.username + '</h3>' + data.message +
                '</div>' +
                '</li>';
            if (notificationNumber == 1) {
                $("#notitfications").html(html);
            }else{
                $("#notitfications").append(html);
            }
            showHideNotitificationCount();
        });

        // show/hide notification count
        function showHideNotitificationCount() {
            if (notificationNumber > 0) {
                $('.notification-number').text(notificationNumber).removeClass('hidden');
            } else {
                $('.notification-number').text(0).addClass('hidden');
            }
        }

        // show notification div
        function showDiv(e) {
            var divID = $(e).data('target');
            $("#" + divID).toggle('slow');
        }
    </script>
</body>

</html>
```
### Create notification send view
``` sendNotification.blade.php ```
```base
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="antialiased bg-gray-900 p-4">

    <div class="flex justify-end">
        <div class="absolute space-y-1" id="alerts"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="col-span-1"></div>
        <div class="col-span-1">
            <form enctype="multipart/form-data" action="{{ route('send-notification') }}" method="post" id="sendForm">
                @csrf
                <div class="p-2 text-center">
                    <h3 class="text-white font-bold text-3xl">Send Message</h3>
                </div>
                <div class="p-2 grid gap-1">
                    <label class="text-white">Enter Name : </label>
                    <input type="text" class="rounded-sm p-1 text-gray-700" name="name" placeholder="Enter Name">
                </div>
                <div class="p-2 grid gap-1">
                    <label class="text-white">Enter Message : </label>
                    <input type="text" class="rounded-sm p-1 text-gray-700" name="message" placeholder="Enter Message">
                </div>
                <div class="p-2 grid mt-2">
                    <button class="bg-green-400 text-white rounded-md p-2">Send</button>
                </div>
            </form>
        </div>
        <div class="col-span-1"></div>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>
        function showDiv(e) {
            var divID = $(e).data('target');
            $("#" + divID).toggle('slow');
        }

        // form submit to send notification
        $('form').on('submit', function(e) {
            e.preventDefault();
            var data = new FormData(e.target);
            $("#alerts").html('');
            $.ajax({
                url: e.target.action,
                type: e.target.method,
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function($response) {
                    if ($response) {
                        appendSuccess($response.message);
                    }
                },
                error: function($error) {
                    if ($error.status === 422) {
                        if (
                            Object.keys($error.responseJSON).length > 0 &&
                            Object.keys($error.responseJSON.errors).length > 0
                        ) {
                            var errors = $error.responseJSON.errors;
                            $.each(errors, function(index, value) {
                                appendError(value[0]);
                            })
                        }
                    } else {
                        appendError($error.responseJSON.message);
                    }
                }
            });
        });

        // error message
        function appendError(message) {
            var id = '_' + Math.random().toString(36).substr(2, 9);
            var html =
                '<div class="bg-red-100 md:w-full border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" id="' +
                id + '">' +
                '<div class="grid grid-cols-4">' +
                '<span class="block sm:inline col-span-3">' + message + '</span>' +
                '<span class="absolute top-0 bottom-0 right-0 px-4 py-3 col-span-1" onclick="showDiv(this)" data-target="' +
                id + '">' +
                '<svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">' +
                '<title>Close</title>' +
                '<path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />' +
                '</svg>' +
                '</span>' +
                '</div>' +
                '</div>';
            $("#alerts").append(html);
        }

        // success message
        function appendSuccess(message) {
            var id = '_' + Math.random().toString(36).substr(2, 9);
            var html =
                '<div class="bg-green-100 md:w-full border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" id="' +
                id + '">' +
                '<div class="grid grid-cols-4">' +
                '<span class="block sm:inline col-span-3">' + message + '</span>' +
                '<span class="absolute top-0 bottom-0 right-0 px-4 py-3 col-span-1" onclick="showDiv(this)" data-target="' +
                id + '">' +
                '<svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">' +
                '<title>Close</title>' +
                '<path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />' +
                '</svg>' +
                '</span>' +
                '</div>' +
                '</div>';
            $("#alerts").append(html);
        }
    </script>
</body>

</html>
```
### Create route
``` routes/web.php ```
```base
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

// Set route for view notification
Route::get('/', function () {
    return view('welcome');
});

// Set route for view sended notification
Route::get('/send-notification', function(){
    return view('sendNotification');
});

// Set route for send notification
Route::post('/send-notification',[ Controller::class, 'sendNotification' ] )->name('send-notification');

```

### Send notification function
``` App\Http\Controllers\Controller.php ```
```base
// add top off the controller
use App\Events\SenMessage;


// send notification
public function sendNotification( Request $request ) {
        $validated = $request->validate( [
            'name' => 'required',
            'message' => 'required',
        ] );

        event( new SenMessage( $request->name, $request->message ) );
        return response()->json(
            array(
                'message' => 'Message send successfully.',
                'status' => 'success',
                'code' => 200
            ),
            200
        );
    }
```

### Testing
```base
Base URL : 
[ Your Hostname ]/

Send Notification URL :
[ Your Hostname ]/send-notification
```