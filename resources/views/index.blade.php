<html>
<head>
    <title>Arlo summary</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        textarea {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;

            width: 100%;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-sm-3">
        <label for="user_id">User Id</label>
        <input name="user_id" value="{{ $user_id }}" disabled>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label for="token">Token</label>
        <textarea name="token" disabled>{{ $token }}</textarea>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <button class="btn btn-block btn-success btn-lg" id="arm">Arm</button>
    </div>
    <div class="col-sm-6">
        <button class="btn btn-block btn-warning btn-lg" id="disarm">Disarm</button>
    </div>
</div>
@foreach($devices as $device)
    <div class="row">
        <div class="col-sm-3">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="device{{ $device->deviceId }}_id">Device Id</label>
                        <input name="device{{ $device->deviceId }}_id" value="{{ $device->deviceId }}" disabled>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="device{{ $device->deviceId }}_name">Device name</label>
                        <input name="device{{ $device->deviceId }}_name" value="{{ $device->deviceName }}" disabled>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="device{{ $device->deviceId }}_name">Device Type</label>
                        <input name="device{{ $device->deviceId }}_name" value="{{ $device->deviceType}}" disabled>
                    </div>
                </div>
            </div>
        </div>
        @if ($device->deviceType === 'camera')
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="device{{ $device->deviceId }}_lastimage">Device image</label>
                    <a href="{{ $device->presignedFullFrameSnapshotUrl }}"><img class="img-responsive img-thumbnail" name="device{{ $device->deviceId }}_lastimage" src="{{ $device->presignedLastImageUrl }}" ></a>
                </div>
            </div>
        @elseif ($device->deviceType === 'basestation')
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="device{{ $device->deviceId }}_owner">Owner</label>
                    <input name="device{{ $device->deviceId }}_name" value="{{ $device->owner->firstName }} {{ $device->owner->lastName }}" disabled>
                </div>
            </div>
        @endif
    </div>
@endforeach

</body>
<script>
    $('#disarm').click(function () {
        $.ajax({
            url: '{{ route('disarm') }}',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.success === true)
                    alert("System disarmed");
            },
            error: function (error) {
                alert("ERROR: " + error.statusText);
            }
        });
    });
    $('#arm').click(function () {
        $.ajax({
            url: '{{ route('arm') }}',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.success === true)
                    alert("System armed");
            },
            error: function (error) {
                alert("ERROR: " + error.statusText);
            }
        });
    });
</script>
</html>