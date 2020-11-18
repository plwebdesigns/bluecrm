<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body>
    <h3 class="font-fredricka mb-3">Sale Detail</h3>
    <table class="mb-2 table table-sm">
        @foreach ($sale as $key => $value)
            <tr>
                <td class="font-weight-bold pr-2">{{Str::replaceArray('_', [' '], Str::title($key))}}:</td>
                <td>{{$value}}</td>
            </tr>
        @endforeach
    </table>
    <br>
   @foreach ($commission_breakdown as $key => $item)
   <h5 style="text-decoration: underline">Agent {{$key + 1}}</h5>
    <table class="mb-2">
        @foreach ($item as $k => $value)
            <tr>
                <td class="font-weight-bold pr-2">{{(Str::title($k))}}:</td>
                <td>{{$value}}</td>
            </tr>
        @endforeach
    </table>
    @endforeach
</body>
</html>
