@extends('layouts.one')


@section('content')
    <form id="addSaleForm" method="POST" action="/add_sale/new">
        @csrf
        <div class="row mt-2">
            <div class="col-lg-4">
                <h3>Sale Info</h3>
                <table class="table table-sm table-borderless">
                    @foreach($form['fields'] as $field)
                        <tr>
                            <th>
                                <label for="{{$field['name']}}">
                                    {{preg_replace('/_/', ' ', strtoupper($field['name']))}}:
                                </label>
                            </th>
                            <td>
                                @if($field['type'] === 'select')
                                    <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="custom-select">
                                        <option value="">---Select One---</option>
                                        @foreach($field['options'] as $item)
                                            <option
                                                value="{{ $item }}"
                                                {{ (old($field['name']) === $item) ? 'selected' : '' }}
                                            >
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] === 'date')
                                    <input name="{{$field['name']}}" class="form-control" type="date"
                                           value="{{ old($field['name']) }}"/>
                                @else
                                    <input name="{{$field['name']}}"
                                           class="form-control"
                                           value="{{ old($field['name']) }}"
                                           type="{{ $field['type'] }}" placeholder="{{ $field['placeholder'] ?? '' }}"/>
                                @endif
                                @if (count($errors) > 0)
                                    <small class="text-danger">
                                        @error($field['name'])
                                        {{$message}}
                                        @enderror
                                    </small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @include('admin.commission_breakdown')
        </div>
        <div class="row mt-2">
            <div class="col-lg-2">
                <button id="subBtn" class="btn btn-primary" type="submit">Submit</button>
                <button class="btn btn-danger" type="reset" data-toggle="tooltip" data-placement="top" title="Reset all fields">Reset</button>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Listen for loss of focus on number type input
            // Get all split inputs and check if it adds up to more
            // than 100
            let elem = $("input[name$='[split]']");
            elem.blur(function () {
                let total = 0;
                elem.each(function () {
                    if (!isNaN($(this).val())){
                        total += parseFloat($(this).val());
                    }
                    if (total > 100){
                        $(this).addClass('is-invalid');
                        $(this).attr({
                            'data-toggle': 'tooltip',
                            'data-placement': 'top',
                            title: 'Split for all agents cannot exceed 100%'
                        });
                        $(this).tooltip('show');
                        return false;
                    }
                    else if ($(this).hasClass('is-invalid') && total <= 100){
                        $(this).removeClass('is-invalid');
                        $(this).tooltip('hide');
                        $(this).removeAttr('data-toggle data-placement data-original-title title');
                    }
                })
            });

            // Get all sale_credit inputs
            // and check if adds up to more than 100
            const el = $("select[name$='[percent_of_sale]']");
            el.blur(function (){
                let sale_credit_total = 0;
                el.each(function () {
                    let val = $(this).val();
                    if (val !== ''){
                        sale_credit_total += parseInt(val);
                    }
                    if (sale_credit_total > 100 && !$(this).hasClass('is-invalid')){
                        $(this).addClass('is-invalid');
                        $(this).attr({
                            'data-toggle': 'tooltip',
                            'data-placement': 'top',
                            title: 'Sale credit for all agents cannot exceed 100%'
                        });
                        $(this).tooltip('show');
                        return false;
                    }
                    else if ($(this).hasClass('is-invalid')){
                        $(this).removeClass('is-invalid');
                        $(this).tooltip('hide');
                        $(this).removeAttr('data-toggle data-placement data-original-title title');
                    }
                });
            });

            // Submit button clicked
            $('#subBtn').click(function (e) { 
                const btn = e.target;
                $('#subBtn').attr('disabled', true);
                btn.form.submit();
            });
        });
    </script>
@endsection
