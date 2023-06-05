@extends('backend.layouts.grid')
@section('title', admin_lang('Generated Images Settings'))
@section('content')
  <div className="image-price-container">
    <form method="post" action="{{route('admin.images.price.save')}}">
      @csrf
      <label>Original Image Price</label>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">$</span>
        </div>
        <input type="text" name="originalPrice" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Original Image Price" value="{{$setting->originalPrice}}">
      </div>

      <label>Discounted Image Price</label>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">$</span>
        </div>
        <input type="text" name="discountPrice" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Discounted Image Price" value="{{$setting->discountPrice}}">
      </div>

      <label>Discounted Image Message</label>
      <div class="input-group mb-3">
        <input type="text" name="discountMessage" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="Discounted Image Message" value="{{$setting->discountMessage??''}}">
      </div>

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
@endsection
