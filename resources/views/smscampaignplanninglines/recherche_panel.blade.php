<form action="{{ action($index_route) }}">

    @if($campaign)
    <input type="hidden" name="planningline_id" value="{{ $campaign->id }}">
    @endif

    <div class="container">
        <div class="d-flex flex-wrap justify-content-start">

            <div class="col-4">

                <select class="form-control" name="treatmentresult[]" id="treatmentresult" multiple="multiple">
                    @if(isset($treatmentresult))
                        @forelse ($treatmentresult as $id => $display)
                            <option value="{{ $id }}" selected>{{ $display }}</option>
                        @empty
                        @endforelse
                    @endif
                </select>

            </div>

            <div class="col-4">

                <div class="input-group form-inline mb-4" tyle="display: inline-block">

                    <div class="input-daterange input-group" id="date-range">
                        <input name="dt_deb" type="text" class="form-control" name="start" placeholder="Début" value="{{ old('dt_deb', $dt_deb ?? '') }}" />
                        <input name="dt_fin" type="text" class="form-control" name="end" placeholder="Fin" value="{{ old('dt_fin', $dt_fin ?? '') }}" />

                        <span class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary"><i class="ti-search"></i></button>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>

</form>
