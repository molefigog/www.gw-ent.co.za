<!DOCTYPE html>
<html>
<head>
    <title>Weekly Notification</title>
</head>
<body>
    @if ($item)
    <h1>{{ $item->title }}</h1>
    <p>Downloads: {{ $item->downloads }}</p>
    <p>Description: {{ $item->description }}</p>
    @endif <!-- Add this line to close the @if block -->
    <h2>Downloaded by Users:</h2>
    <ul>
        @foreach($downloadedUsers as $user)
            <li>{{ $user->name }} (ID: {{ $user->id }})</li>
        @endforeach
    </ul>
</body>
</html>
