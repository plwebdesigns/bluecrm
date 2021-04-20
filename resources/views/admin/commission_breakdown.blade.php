<div class="col-lg-8">
    <div class="row">
        @foreach($segment as $no => $seg)
            @if($no < 2)
                <div class="col-md-6">
                    <h4 class="text-center">Agent #{{ $no + 1 }}</h4>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>AGENT NAME:</th>
                            <td>
                                <select name="{{ 'segment[' . $no . '][agent]' }}" class="custom-select">
                                    <option value="">---Select One---</option>
                                    @foreach($agents as $n => $agent)
                                        <option
                                            value="{{ $agent }}"
                                            {{ (old('segment.' . $no . '.agent') === $agent) ? 'selected' : '' }}
                                        >
                                            {{ $agent }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('segment.' . $no . '.agent')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>SPLIT:</th>
                            <td>
                                <input
                                    name="segment[{{$no}}][split]"
                                    type="number"
                                    class="form-control"
                                    value="{{ old('segment.' . $no . '.split') }}"
                                    placeholder="45"
                                    max="100"
                                />
                                <small class="text-danger">
                                    @error('segment.' . $no . '.split')
                                    {{$message}}
                                    @enderror
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th>COMMISSION:</th>
                            <td>
                                <input
                                    name="segment[{{$no}}][commission]"
                                    type="number"
                                    class="form-control"
                                    value="{{ old('segment.' . $no . '.commission') }}"
                                    placeholder="5000"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th>SALE CREDIT:</th>
                            <td>
                                <select class="custom-select" name="segment[{{$no}}][percent_of_sale]">
                                    <option value="">---Select One---</option>
                                    <option value="100" {{ (old('segment.' . $no . '.percent_of_sale') === '100') ? 'selected' : '' }}>100%</option>
                                    <option value="50" {{ (old('segment.' . $no . '.percent_of_sale') === '50') ? 'selected' : '' }}>50%</option>
                                    <option value="0" {{ (old('segment.' . $no . '.percent_of_sale') === '0') ? 'selected' : '' }}>0%</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Team Sale:</th>
                            <td>
                                <select class="custom-select" name="segment[{{ $no }}][split_sale]">
                                    <option value="No" {{ (old('segment.' . $no . '.split_sale') === 'No') ? 'selected' : '' }}>No</option>
                                    <option value="Yes" {{ (old('segment.' . $no . '.split_sale') === 'Yes') ? 'selected' : '' }}>Yes</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Membership Dues</th>
                            <td>
                                <input
                                        name="segment[{{$no}}][membership_dues_paid]"
                                        type="number"
                                        class="form-control"
                                        value="{{ old('segment.' . $no . '.membership_dues_paid') }}"
                                        placeholder="50"
                                />
                            </td>
                        </tr>
                    </table>
                </div>
            @endif
        @endforeach
        <ul id="errorList">
        @foreach ($errors->get('segment') as $item)
            <li class="text-danger">{{ $item }}</li>
        @endforeach
        </ul>
        
            

    </div>
    <div class="row justify-content-center" id="panel1">
        <div class="accordion" id="accordionOne">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            More Agents
                        </button>
                    </h2>
                </div>
                <div class="row collapse" aria-labelledby="headingOne" data-parent="#accordionOne" id="collapseOne">
                    @foreach($segment as $no => $seg)
                        @if($no >= 2 && $no < 4)
                            <div class="col-md-6">
                                <h4 class="text-center">Agent #{{ $no + 1 }}</h4>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>AGENT NAME:</th>
                                        <td>
                                            <select name="{{ 'segment[' . $no . '][agent]' }}" class="custom-select">
                                                <option value="">---Select One---</option>
                                                @foreach($agents as $n => $agent)
                                                    <option
                                                        value="{{ $agent }}"
                                                        {{ (old('segment.' . $no . '.agent') === $agent) ? 'selected' : '' }}
                                                    >
                                                        {{ $agent }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SPLIT:</th>
                                        <td>
                                            <input
                                                name="segment[{{$no}}][split]"
                                                type="number"
                                                class="form-control"
                                                value="{{ old('segment.' . $no . '.split') }}"
                                            />
                                            <small class="text-danger">
                                                @error($segment[$no]['split'])
                                                {{$message}}
                                                @enderror
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>COMMISSION:</th>
                                        <td>
                                            <input
                                                name="segment[{{$no}}][commission]"
                                                type="number"
                                                class="form-control"
                                                value="{{ old('segment.' . $no . '.commission') }}"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SALE CREDIT:</th>
                                        <td>
                                            <select class="custom-select" name="segment[{{$no}}][percent_of_sale]">
                                                <option value="">---Select One---</option>
                                                <option value="100">100%</option>
                                                <option value="50">50%</option>
                                                <option value="0">0%</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Team Sale:</th>
                                        <td>
                                            <select class="custom-select" name="segment[{{ $no }}][split_sale]">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" id="panel2">
        <div class="accordion" id="accordionTwo">
            <div class="card">
                <div class="card-header" id="headingTwo" hidden>
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left text-center" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Additional Agents
                        </button>
                    </h2>
                </div>
                <div class="row collapse" aria-labelledby="headingTwo" data-parent="#accordionTwo" id="collapseTwo">
                    @foreach($segment as $no => $seg)
                        @if($no > 3)
                            <div class="col-md-6">
                                <h4 class="text-center">Agent #{{ $no + 1 }}</h4>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>AGENT NAME:</th>
                                        <td>
                                            <select name="{{ 'segment[' . $no . '][agent]' }}" class="custom-select">
                                                <option value="">---Select One---</option>
                                                @foreach($agents as $n => $agent)
                                                    <option
                                                        value="{{ $agent }}"
                                                        {{ (old('segment.' . $no . '.agent') === $agent) ? 'selected' : '' }}
                                                    >
                                                        {{ $agent }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SPLIT:</th>
                                        <td>
                                            <input
                                                name="segment[{{$no}}][split]"
                                                type="number"
                                                class="form-control"
                                                value="{{ old('segment.' . $no . '.split') }}"
                                            />
                                            <small class="text-danger">
                                                @error($segment[$no]['split'])
                                                {{$message}}
                                                @enderror
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>COMMISSION:</th>
                                        <td>
                                            <input
                                                name="segment[{{$no}}][commission]"
                                                type="number"
                                                class="form-control"
                                                value="{{ old('segment.' . $no . '.commission') }}"
                                            />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SALE CREDIT:</th>
                                        <td>
                                            <select class="custom-select" name="segment[{{$no}}][percent_of_sale]">
                                                <option value="">---Select One---</option>
                                                <option value="100">100%</option>
                                                <option value="50">50%</option>
                                                <option value="0">0%</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Team Sale:</th>
                                        <td>
                                            <select class="custom-select" name="segment[{{ $no }}][split_sale]">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function (){
        const accordionOne = $('#collapseOne');
        const accordionTwo = $('#collapseTwo');


        accordionOne.on('shown.bs.collapse', function (){
            $('#headingTwo').attr('hidden', false);
        });
        accordionOne.on('hidden.bs.collapse', function (){
            $('#headingTwo').attr('hidden', true);
            if (accordionTwo[0].classList.contains('show')){
                accordionTwo[0].classList.remove('show');
            }
        });

    });
</script>

