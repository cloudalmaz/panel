@extends('layouts.admin')
<?php 
    // Define extension information.
    $EXTENSION_ID = "redirect";
    $EXTENSION_NAME = "Redirect";
    $EXTENSION_VERSION = "1.3.2";
    $EXTENSION_DESCRIPTION = "Create URL redirects in seconds.";
    $EXTENSION_ICON = "/assets/extensions/redirect/icon.jpg";
    $EXTENSION_WEBSITE = "http://github.com/prplwtf/blueprint-redirect";
    $EXTENSION_WEBICON = "bx bx-git-branch";
?>
@include('blueprint.admin.template')

@section('title')
    {{ $EXTENSION_NAME }}
@endsection

@section('content-header')
    @yield('extension.header')
@endsection

@section('content')
    @yield('extension.config')
    @yield('extension.description')<div class="row">
  <div class="col-xs-3">

    <!-- Redirects -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class='bx bxs-direction-left' style='margin-right:5px;'></i></i>Redirects</h3>
      </div>
      <div class="box-body">
        <code>{{ $redirects }}</code>
      </div>
    </div>

  </div>
  <div class="col-xs-9">

    <!-- Add Redirect -->
    <form action="" method="POST">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class='bx bxs-folder-plus' style='margin-right:5px;'></i></i>Add Redirect</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-3">
              <label class="control-label">Name</label>
              <input type="text" required name="rname" id="rname" value="" class="form-control"/>
              <p class="text-muted small">This will be used for the redirect path.</p>
            </div>
            <div class="col-xs-9">
              <label class="control-label">Destination</label>
              <input type="text" required name="rurl" id="rurl" value="" class="form-control"/>
              <p class="text-muted small">Users will be redirected to this url. Must start with HTTP or HTTPS.</p>
            </div>
          </div>
        </div>
        <div class="box-footer">
          {{ csrf_field() }}
          <button type="submit" name="_method" value="PATCH" class="btn btn-gray-alt btn-sm pull-right">Add</button>
        </div>
      </div>
    </form>

    <!-- Remove Redirect -->
    <form action="" method="POST">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class='bx bxs-folder-minus' style='margin-right:5px;'></i></i>Remove Redirect</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-12">
              <label class="control-label">Name</label>
              <input type="text" required name="rdel" id="rdel" value="" class="form-control"/>
              <p class="text-muted small">The redirect you want to remove.</p>
            </div>
          </div>
        </div>
        <div class="box-footer">
          {{ csrf_field() }}
          <button type="submit" name="_method" value="PATCH" class="btn btn-gray-alt btn-sm pull-right">Remove</button>
        </div>
      </div>
    </form>

  </div>
</div>
@endsection
