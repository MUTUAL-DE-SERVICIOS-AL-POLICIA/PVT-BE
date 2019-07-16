<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Serial</th>
        <th>Stock</th>
    </tr>
    </thead>
    <tbody>
    @foreach($eco_coms as $e)
        <tr>
            <td>{{ $e->code }}</td>
            <td>{{ $e->total }}</td>
            <td>{{ $e->stock }}</td>
        </tr>
    @endforeach
    </tbody>
</table>