@extends('backend.layouts.app')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('navigations.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">{{ __('Menu Name') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">URL</label>
                            <input type="text" class="form-control" name="url" id="url"
                                value="{{ old('url') }}" placeholder="https://amperakoding.com">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Icon
                                <span class="badge bg-secondary"><a href="https://fontawesome.com/v6/search?o=r&m=free" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></span>
                            </label>
                            <input type="text" class="form-control" name="icon" id="icon"
                                value="{{ old('icon') }}" placeholder="Ex: fa fa-bullhorn">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">{{ __('Order Number') }}</label>
                            <input type="number" class="form-control" name="sort" id="sort"
                                value="{{ old('sort') }}">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-3"><label for="name">{{ __('SubMenu Name')}}</label></div>
                    <div class="col-md-3"><label for="name">URL</label></div>
                    <div class="col-md-3"><label for="name">Icon <span class="badge bg-secondary"><a href="https://fontawesome.com/v6/search?o=r&m=free" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> See Icon</a></span></label></div>
                    <div class="col-md-3"><label for="name">{{ __('Order Number')}}</label></div>
                </div>

                <div class="form-group" id="dynamicFields">
                    <!-- Display existing submenus -->
                </div>

                <button type="button" class="btn btn-success" id="addRow"><i class="fa fa-plus"></i> {{ __('Add')}}
                    SubMenu</button>
            </div>

            <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                    {{ __('Save') }}</button>
                <button type="reset" name="reset" class="btn btn-danger"><i class="fa fa-sync"></i>
                    {{ __('Cancel') }}</button>
                <a href="{{ route('navigations.index') }}" name="reset" class="btn btn-dark"><i
                        class="fa fa-arrow-left"></i>
                    {{ __('Back') }}</a>
            </div>
            <!-- /.card-body -->
        </div>
    </form>
@endsection

@section('script_addon_footer')
    <script>
        $(document).ready(function() {
            // Counter for dynamic fields
            var counter = 0;

            // Function to add a new row
            $("#addRow").on("click", function() {
                counter++;
                var newRow = `<div class="row mb-3" id="row${counter}">
                    <div class="col-md-3">
                                            <input type="text" class="form-control" name="submenu_name[]">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="submenu_url[]" placeholder="URL">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="submenu_icon[]" placeholder="Icon" placeholder="Ex: fa fa-bullhorn">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="submenu_sort[]" placeholder="Sort">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger" onclick="removeRow(${counter})">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>`;

                $("#dynamicFields").append(newRow);
            });

            // Function to remove a row
            window.removeRow = function(rowId) {
                $("#row" + rowId).remove();
            };
        });
    </script>
@endsection