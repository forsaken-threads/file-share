@extends('layouts.app')

@section('heading')
    Dashboard
@endsection

@section('content')
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#shares" aria-controls="shares" role="tab" data-toggle="tab">
                    <i class="fas fa-share"></i>Files Shared
                </a>
            </li>
            <li role="presentation">
                <a href="#upload" aria-controls="upload" role="tab" data-toggle="tab">
                    <i class="fas fa-upload text-primary"></i> File Upload
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="shares">
                @include('tabs.shares')
            </div>
            <div role="tabpanel" class="tab-pane" id="upload">
                @include('tabs.upload')
            </div>
        </div>

    </div>
@endsection
