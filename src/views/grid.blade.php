{{--@include('include')--}}
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        @foreach ($columns as $column)
        <th>{{{ $column->renderHeader() }}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($rows as $row)
    <tr>
        @foreach ($columns as $column)
        <td>{{{ $column->render($row) }}}
        </td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
    @if ($pagination->isEnabled())
    <tfoot>
        <tr>
            <td colspan="{{ count($columns) }}">
                Footer here
            </td>
        </tr>
    </tfoot>
    @endif
</table>
