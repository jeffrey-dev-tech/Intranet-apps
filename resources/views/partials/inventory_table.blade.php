<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Hostname</th>
            <th>IP</th>
            <th>Department</th>
            <!-- Add more columns as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->hostname }}</td>
                <td>{{ $item->ip_address }}</td>
                <td>{{ $item->department }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
