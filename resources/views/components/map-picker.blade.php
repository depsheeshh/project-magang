<div class="form-group">
  <label>Alamat / Lokasi Rapat</label>
  <input type="text" name="lokasi" class="form-control"
         value="{{ $lokasi ?? '' }}" placeholder="Alamat lengkap atau nama gedung">
</div>

<div class="form-row">
  <div class="form-group col-md-4">
    <label>Latitude</label>
    <input type="text" id="latitude{{ $mapId }}" name="latitude"
           class="form-control" value="{{ $latitude ?? '' }}">
  </div>
  <div class="form-group col-md-4">
    <label>Longitude</label>
    <input type="text" id="longitude{{ $mapId }}" name="longitude"
           class="form-control" value="{{ $longitude ?? '' }}">
  </div>
  <div class="form-group col-md-4">
    <label>Radius (meter)</label>
    <input type="range" id="radius{{ $mapId }}" name="radius"
           class="form-control-range" min="10" max="1000" step="10"
           value="{{ $radius ?? 100 }}"
           oninput="document.getElementById('radiusValue{{ $mapId }}').innerText=this.value">
    <small>Radius: <span id="radiusValue{{ $mapId }}">{{ $radius ?? 100 }}</span> m</small>
  </div>
</div>

<div id="map{{ $mapId }}" style="height: 300px; border:1px solid #ccc;"></div>
