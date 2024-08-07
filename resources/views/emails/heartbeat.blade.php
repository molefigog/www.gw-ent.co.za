<!DOCTYPE html>
<html>
<head>
    <title>Daily Heartbeat Email</title>
</head>
<body>
    @php
        $userNames = App\Models\User::pluck('name')->toArray();
    @endphp
 @php
 $setting = App\Models\Setting::orderBy('created_at', 'desc')
     ->select('site', 'image', 'logo', 'favicon', 'description')
     ->first();
@endphp
    <h1>Daily Heartbeat Email</h1>
    
    <p>Hello, {{ $user->name }}!</p>

    <p>This is your daily heartbeat email. We're checking in to make sure everything is running smoothly.</p>

    <!-- Display the user names -->
    <p>Here are the names of all users: {{ implode(', ', $userNames) }}</p>

    <p>If you have any questions or concerns, please don't hesitate to contact us.</p>

    <p>Best regards,</p>
    <p>{{ $setting->site ?? config('app.name') }}</p>

</body>
</html>
