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
