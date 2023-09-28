<label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kota/Kabupaten</label>
{!! Form::select('city_id', $kota, '', [
    'class' => 'form-control shadow-sm',
    'placeholder' => 'Pilih Kota',
    'id' => 'city_id',
]) !!}