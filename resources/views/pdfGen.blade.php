<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <title>PDF Report</title>
    </head>
    <body>
        <h2 style="border-bottom: 1px solid black">Report for {{$agent ?? ''}} in {{$year ?? ''}}</h2>
        <table class="table table-sm table-borderless mt-3">
            <thead>
                <tr>
                    <th scope="col">TYPE</th>
                    <th scope="col">CLIENT</th>
                    <th scope="col">SALE PRICE</th>
                    <th scope="col">COMMISSION</th>
                    <th scope="col">AGENT COMMISSION</th>
                </tr>
            </thead>
            <tbody style="text-align: left">
                @foreach ($sales as $index => $sale)
                    <tr>
                        <td>{{$sale->type}}</td>
                        <td>{{$sale->client_name}}</td>
                        <td>{{$sale->sale_price}}</td>
                        <td>{{$sale->total_commission}}</td>
                        <td>{{$sale->pivot->commission}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td style="border-top: 2px solid black">{{$totals['sale_price']}}</td>
                    <td style="border-top: 2px solid black">{{$totals['total_commission']}}</td>
                    <td style="border-top: 2px solid black">{{$totals['agent_commission']}}</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
