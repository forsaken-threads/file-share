@push('body-footer')
    <script type="text/javascript">
        $('a[data-toggle="popover"]').popover({
            html: true
        }).on('inserted.bs.popover', function(event) {
            $(event.target).next().find('.popover-content').find('input').select();
            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");
            } catch(e) {
                succeed = false;
            }
            if (!succeed) {
                $(event.target).next().find('.popover-title').text('Copy to Clipboard Failed');
            }
        });

        $('#file-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var fileId = button.data('file-id');
            var that = this;
            $(this).find('form').attr('action', '{{ route('file', '') }}/' + fileId + '/edit');
            $.get({
                url: '{{ route('file', '') }}/' + fileId + '/edit',
                dataType: 'json'
            }).done(function(json) {
                $.each(json, function(key, value) {
                    $('#edit-' + key).val(value).trigger('change');
                });
            }).fail(function() {
                $(that).find('.alert').removeClass('hidden');
                $(that).find('form').addClass('hidden');
            });
        }).on('hide.bs.modal', function() {
            $(this).find('form').attr('action', '');
        });

        $('#edit-visibility').on('change', function() {
            $('.input-toggle.on').toggleClass('on off').find('input').prop('disabled', true).prop('required', false);
            $('.edit-visibility-' + $(this).val()).toggleClass('on off').find('input').prop('disabled', false).prop('required', true);
        });
    </script>
@endpush

<table class="table table-striped">
    <thead>
    <tr>
        <th>File Name</th>
        <th>Shared By (When)</th>
        <th>Downloads</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($files as $file)
        <tr>
            <td>{{ $file->share_name }}</td>
            <td>{{ $file->user->name }} ({{ $file->created_at }})</td>
            <td>{{ $file->downloads }}</td>
            <td>
                <a href="{{ route('file', [$file->name, $file->preview()]) }}" class="text-info"><i class="fas fa-download"></i></a> &nbsp;
                <a role="button" class="text-info" data-toggle="modal" data-target="#file-edit" data-file-id="{{ $file->name }}">
                    <i class="fas fa-edit"></i>
                </a> &nbsp;
                {{--<i class="fas fa-trash text-danger"></i> &nbsp;--}}
                Links:
                @if($file->preview())
                    <a href="#" role="button" tabindex="-1" class="text-primary never-focus"
                       data-placement="top" data-title="Copied to Clipboard" data-toggle="popover"
                       data-content='<input type="text" value="{{ route('file', [$file->name, $file->preview()]) }}" size="{{ strlen(route('file', [$file->name, $file->preview()])) + 5 }}" />' >
                        <i class="fas fa-eye"></i>
                    </a>
                @endif
                <a href="#" role="button" tabindex="-1" class="text-primary never-focus"
                   data-placement="top" data-title="Copied to Clipboard" data-toggle="popover"
                   data-content='<input type="text" value="{{ route('file', [$file->name]) }}" size="{{ strlen(route('file', [$file->name])) + 3 }}" />' >
                    <i class="fas fa-download"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3">{none}</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="modal fade" id="file-edit" tabindex="-1" role="dialog" aria-labelledby="file-edit-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="file-edit-label">Edit Shared File</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissable hidden">
                    There was a problem loading the file information. Please try again.
                </div>
                <form method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="edit-share-name" class="control-label">Share Name</label>
                        <input type="text" class="form-control" id="edit-share-name" name="edit_share_name" required />
                    </div>
                    <div class="form-group">
                        <label for="edit-force-download">Force Download</label>
                        <select class="form-control" id="edit-force-download" name="edit_force_download">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-visibility">Visibility</label>
                        <select id="edit-visibility" name="edit_visibility" class="form-control">
                            @foreach (\App\Visibility::all() as $visibility)
                                <option value="{{ $visibility->id }}">{{ $visibility->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group input-toggle off edit-visibility-3">
                        <label for="edit-password">Password</label>
                        <input type="text" class="form-control password" id="edit-password" name="edit_password" autocomplete="off" disabled />
                    </div>
                    <div class="form-group input-toggle off edit-visibility-5">
                        <label for="edit-allowed-users">Allowed Users</label>
                        <input type="text" class="form-control" id="edit-allowed-users" name="edit_allowed_users" disabled />
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary pull-right" value="Save Share" />
                </form>
            </div>
        </div>
    </div>
</div>
