@extends('backend.layouts.grid')
@section('title', admin_lang('Generated Images Settings'))
@section('content')
  <div class="d-flex mb-3 align-items-center" >
    @csrf
    <h1 style="margin-right: auto;">Custom Inputs</h1>
    <div>
      <button class="btn btn-primary" onclick="add()"><i class="fa fa-plus"></i>Add Field</button>
    </div>
  </div>
  <div>
    @foreach($image_settings as $setting)
      <div class="custom-inputs-container border p-3 rounded mt-3">
        <div class="first-row d-flex">
          <div class="inputs row" style="flex: 1;">
            <div class="col-6">
              <input type="text" id="details-{{$setting->id}}" name class="form-control" placeholder="Property Details" value="{{$setting->details}}" />
            </div>
            <div class="col-6">
              <select class="form-select" id="type-{{$setting->id}}" onchange="toggleCategories(event, {{$setting->id}})">
                <option value="text-field" {{$setting->type=="text-field"?"selected":""}}>Text Field</option>
                <option value="multi-select-field" {{$setting->type=="multi-select-field"?"selected":""}}>Multi Select Field</option>
              </select>
            </div>
          </div>
          <div class="action ms-3 d-flex align-items-center">
            <button class="btn btn-link me-1" onclick="removeSetting({{$setting->id}})"><i class="fa fa-trash text-danger" aria-hidden="true"></i></button>
            <div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="visibility-{{$setting->id}}" {{($setting->visibility)?"checked":""}} >
                <label class="form-check-label" for="flexSwitchCheckChecked">Visibility</label>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="enabled-{{$setting->id}}" {{($setting->enabled)?"checked":""}} >
                <label class="form-check-label" for="flexSwitchCheckChecked">Enable</label>
              </div>
            </div>
          </div>
        </div>

        <div class="">
          <input type="text" id="description-{{$setting->id}}" name class="form-control" placeholder="Property Description" value="{{$setting->description}}" />
        </div>

        <div class="multiple-selector my-3 {{$setting->type=='text-field'?'d-none':''}}" id="categories-container-{{$setting->id}}">
          <input type="text" id="categories-{{$setting->id}}" name class="form-control" placeholder="Write options by space" value="{{$setting->categories}}" />
        </div>

        <div class="second-row mt-3 d-flex align-items-center">
          <input type="text" id="placeholder-{{$setting->id}}" placeholder="Place Holder" class="form-control" value="{{$setting->placeholder}}" />
          <button class="btn btn-success ms-2" onclick="save({{$setting->id}})"><i class="fa-regular fa-floppy-disk"></i>Save</button>
        </div>
      </div>
    @endforeach

  </div>

  <script>
    function toggleCategories(event, id) {
      const v = event.target.value
      const el = document.getElementById("categories-container-"+id)
      console.log(el, event, id)
      if (v == "multi-select-field") el.classList.remove("d-none")
      else el.classList.add("d-none")
    }

    const API = "https://custominkstudio.com/admin/images/settings"

    function post(path, params, method='post', request_method="POST") {
      const token = document.getElementsByName("_token")[0].value
      const form = document.createElement('form');
      form.method = method;
      form.action = path;

      params = {
        ...params,
        '_token': token,
        'request_method': request_method
      }

      for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];

            form.appendChild(hiddenField);
          }
        }

      document.body.appendChild(form);
      form.submit();
    }

    function add() {
      post(API, {})
    }

    function save(id) {
      const details = document.getElementById("details-"+id).value
      const type = document.getElementById("type-"+id).value
      const placeholder = document.getElementById("placeholder-"+id).value
      const categories = document.getElementById("categories-"+id).value
      const visibility = document.getElementById("visibility-"+id).checked?1:0
      const enabled = document.getElementById("enabled-"+id).checked?1:0
      const description = document.getElementById("description-"+id).value

      post(API, {details, type, placeholder, id, visibility, categories, enabled, description}, "post", "PUT")
    }

    function removeSetting(id) {
      post(API, {id}, "post", "DELETE")
    }
  </script>
@endsection
