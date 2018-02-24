@push('body-footer')
    <script type="text/javascript">
        $('#visibility').on('change', function() {
            $('.input-toggle.on').toggleClass('on off').find('input').prop('disabled', true).prop('required', false);
            $('.visibility-' + $(this).val()).toggleClass('on off').find('input').prop('disabled', false).prop('required', true);
        })
    </script>
@endpush

<form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="shared_file"></label>
        <input type="file" id="shared_file" name="shared_file" required />
    </div>
    <div class="form-group">
        <label for="visibility">Visibility</label>
        <select id="visibility" name="visibility" class="form-control">
            @foreach (\App\Visibility::all() as $visibility)
                <option value="{{ $visibility->id }}">{{ $visibility->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group input-toggle off visibility-3">
        <label for="shared-password">Password</label>
        <input type="text" class="form-control password" id="shared-password" name="shared_password" autocomplete="off" disabled />
    </div>
    <div class="form-group input-toggle off visibility-5">
        <label for="shared-allowed-users">Allowed Users</label>
        <input type="text" class="form-control" id="shared-allowed-users" name="shared_allowed_users" disabled />
    </div>
    <input class="btn btn-default btn-primary" type="submit" value="Upload" />
</form>