@extends('layouts.app')

@section('heading')
    Public Shares
@endsection

@push('body-footer')
    <script type="text/javascript">
        $('#file-password').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var fileId = button.data('file-id');
            $(this).find('form').attr('action', '{{ route('file', ['']) }}/' + fileId);
        }).on('shown.bs.modal', function() {
            $('#file-password-input').focus();
        }).on('hidden.bs.modal', function() {
            $(this).find('form').attr('action', '');
        });

        $('form').on('submit', function() {
            $('#file-password').modal('hide');
        })
    </script>
@endpush

@section('content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Shared By - At </th>
                <th><i class="fas fa-eye"></i> / <i class="fas fa-download"></i> - Downloads</th>
            </tr>
        </thead>
        <tbody>
            @forelse($files as $file)
                <tr>
                    <td>{{ $file->share_name }}</td>
                    <td>{{ $file->user->name }} - {{ $file->created_at }} </td>
                    <td>
                        @if($file->visibility == \App\Visibility::PUBLIC_WITHOUT_PASSWORD)
                            <a href="{{ route('file', [$file->name, $file->preview()]) }}">
                                <i class="fas fa-{{ $file->preview() ? 'eye' : 'download' }} text-success"></i>
                            </a>
                        @else
                            <a role="button" class="text-danger" data-toggle="modal" data-target="#file-password" data-file-id="{{ $file->name }}/{{ $file->preview() }}">
                                <i class="fas fa-download" title="Requires Password"></i>
                            </a>
                        @endif
                        - {{ $file->downloads }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">{none}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="modal fade" id="file-password" tabindex="-1" role="dialog" aria-labelledby="file-password-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="file-password-label">This file requires a password</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="file-password-input" class="control-label">Password</label>
                            <input type="text" class="password form-control" id="file-password-input" name="file_password" autocomplete="off" required />
                        </div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary pull-right" value="Download File" />
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection