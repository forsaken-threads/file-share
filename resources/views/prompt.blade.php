@extends('layouts.app')

@push('body-footer')
    <script type="text/javascript">
        $('form').on('submit', function() {
            $(this).addClass('hidden');
        })
    </script>
@endpush

@section('heading')
    Shared File Requires A Password
@endsection

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>File Name</th>
            <th>Shared By (When)</th>
            <th>Downloads</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $file->share_name }}</td>
                <td>{{ $file->user->name }} ({{ $file->created_at }})</td>
                <td>{{ $file->downloads }}</td>
            </tr>
        </tbody>
    </table>
    <form method="post" action="{{ route('file', $file->name) }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="file-password-input" class="control-label">Password</label>
            <input type="text" class="password form-control" id="file-password-input" name="file_password" autocomplete="off" required />
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <input type="submit" class="btn btn-primary pull-right" value="Download File" />
    </form>
@endsection