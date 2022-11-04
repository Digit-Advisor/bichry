@if (Session('error'))
    <div class="alert alert-danger">
        {!!Session('error')!!}
    </div>
@endif

  <!-- Success message -->
  @if(Session('success'))
  <div class="alert text-dark alert-success" style="color: black !important">
      {!!Session('success')!!}
  </div>
  @endif


  @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
