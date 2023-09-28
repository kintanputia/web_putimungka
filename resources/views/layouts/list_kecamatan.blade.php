<label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kecamatan</label>
{!! Form::select('kecamatan_id', $kecamatan, '', [
    'class' => 'form-control shadow-sm',
    'placeholder' => 'Pilih Kecamatan',
    'id' => 'kecamatan_id',
]) !!}