<h3>VPN Accounts with Expired Passwords</h3>

@if($expiredVpns->isEmpty())
    <p>No VPN passwords are expired at this time.</p>
@else
    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expiredVpns as $index => $vpn)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $vpn->username }}</td>
                <td>{{ $vpn->email }}</td>
                <td>{{ $vpn->updated_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
