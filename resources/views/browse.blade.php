@extends('facilitador::master')

@section('page_header')
    <h1 class="page-title">
        <i class="facilitador-hook"></i> Hooks
        <div class="btn btn-success install">
            <i class="facilitador-plus"></i> Add hook
        </div>
    </h1>
@stop

@section('page_header_actions')

@stop

@section('content')
    <div class="page-content container-fluid">
        @if (request()->has('message'))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-{{ request('message_type', 'info') }}">
                        <p>{{ request('message') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if($daysSinceLastCheck >= 10)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <p>You have not checked for any updates for the last {{ $daysSinceLastCheck }} days.</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card card-bordered">
                    <div class="box-body panel-body card-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Enabled</th>
                                <th class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hooks as $hook)
                                <tr>
                                    <td>
                                        <i class="facilitador-{{ $hook->type }}"></i> {{ $hook->name }}
                                    </td>
                                    <td>
                                        <?php echo ($hook->enabled ? '<i class="facilitador-check"></i> ENABLED' : '<i class="facilitador-x"></i> DISABLED') ?>
                                    </td>
                                    <td class="no-sort no-click">
                                        <div class="btn-sm btn-danger float-right delete" data-id="{{ $hook->name }}" id="delete-{{ $hook->name }}">
                                            <i class="facilitador-trash"></i> Uninstall
                                        </div>
                                        <a href="{{ route('rica.facilitador.hooks.'.($hook->enabled ? 'disable' : 'enable'), $hook->name) }}" class="btn-sm btn-primary float-right edit">
                                            <i class="facilitador-edit"></i> {{ $hook->enabled ? 'Disable' : 'Enable' }}
                                        </a>
                                        <?php /*
                                        @if ($hook->hasUpdateAvailable())
                                            <a href="{{ route('rica.facilitador.hooks.update', $hook->name) }}" class="btn-sm btn-warning float-right update">
                                                <i class="facilitador-edit"></i> Update
                                            </a>
                                        @endif
                                        */ ?>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="facilitador-trash"></i> Are you sure you want to uninstall
                        this hook?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('rica.facilitador.hooks') }}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger float-right delete-confirm"
                               value="Yes, Delete This Hook">
                    </form>
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">{{ __('pedreiro::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-success fade" tabindex="-1" id="install_modal" role="dialog">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('rica.facilitador.hooks') }}" id="install_form" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="facilitador-plus"></i>Install new hook.</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name of hook">
                    </div>
                </div>
                <div class="modal-footer">
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-success float-right install-confirm"
                               value="Install">
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">{{ __('pedreiro::generic.cancel') }}</button>
                </div>
            </form><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <!-- DataTables -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({ "order": [] });
        });

        $('.page-title').on('click', '.install', function (e) {
            var form = $('#install_form')[0];

            $('#install_modal').modal('show');
        });

        $('td').on('click', '.delete', function (e) {
            var form = $('#delete_form')[0];

            form.action = parseActionUrl(form.action, $(this).data('id'));

            $('#delete_modal').modal('show');
        });

        function parseActionUrl(action, id) {
            return action.match(/\/[0-9]+$/)
                    ? action.replace(/([0-9]+$)/, id)
                    : action + '/' + id;
        }
    </script>
@stop
