<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    @if(session('flash_message'))
        <div class="flash-message">
            {{ session('flash_message') }}
        </div>
    @endif

    @yield('content')
</body>
</html>
