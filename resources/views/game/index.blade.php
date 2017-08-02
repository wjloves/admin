@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">text</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('play') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">amount</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="amount" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-md-4 control-label">code</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="code" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-md-4 control-label">lottery</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="lottery" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-md-4 control-label">issue</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="issue" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label  class="col-md-4 control-label">sitno</label>

                            <div class="col-md-6">
                                <input  type="text" class="form-control" name="sitno" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    提交
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
