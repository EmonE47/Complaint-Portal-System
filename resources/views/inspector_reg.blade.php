<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspector Registration</title>
    <link href="{{ asset('css/inspector_register.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="form-container">
        <h2>Inspector Registration</h2>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inspector.register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="number">Phone Number:</label>
                <input type="text" id="number" name="number" value="{{ old('number') }}" required>
            </div>

            <div class="form-group">
                <label for="nid_number">NID Number:</label>
                <input type="text" id="nid_number" name="nid_number" value="{{ old('nid_number') }}" required>
            </div>

            <div class="form-group">
                <label for="rank">Rank:</label>
                <select id="rank" name="rank" required>
                    <option value="">Select Rank</option>
                    <option value="inspector" {{ old('rank') == 'inspector' ? 'selected' : '' }}>Inspector</option>
                    <option value="si" {{ old('rank') == 'si' ? 'selected' : '' }}>Sub-Inspector (SI)</option>
                    <option value="asi" {{ old('rank') == 'asi' ? 'selected' : '' }}>Assistant Sub-Inspector (ASI)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="police_station_id">Police Station:</label>
                <select id="police_station_id" name="police_station_id" required>
                    <option value="">Select Police Station</option>
                    @foreach($policeStations as $station)
                        <option value="{{ $station->id }}" {{ old('police_station_id') == $station->id ? 'selected' : '' }}>
                            {{ $station->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <button type="button" class="login-btn" onclick="window.location.href='{{ route('login') }}'">
            Back to Login
        </button>
    </div>
</body>
</html>
