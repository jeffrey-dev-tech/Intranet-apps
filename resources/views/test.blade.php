<!DOCTYPE html>
<html>
<head>
    <title>Test User Fetch</title>
</head>
<body>

<h3>Matching members:</h3>
<ul>
@foreach ($results as  $row)
    <li>{{ $row->team_name}} — {{ $row->team_name}}</li>
@endforeach
</ul>


</body>
</html>
