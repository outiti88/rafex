<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bon de distribution</title>
    <style>
        .top_rw {
            background-color: #ffffff;
        }
        .page-break {
                    page-break-after: always;
        }
        .td_w {}

        button {
            padding: 5px 10px;
            font-size: 14px;
        }

        .invoice-box {
           /*max-width: 890px;*/
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 10px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-bottom: solid 1px #ccc;
        }

        b {
            color: black;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: middle;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: left;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 25px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 12px;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>

    @foreach ($commandesPerPages as $index => $commandesPerPage)
    <div class="invoice-box page-break">
        <table cellpadding="0" cellspacing="0">
            <tbody>
                <tr class="top_rw" >
                    <td colspan="1" style="width : 50%;">
                        <img style=" width: 200px;"
                            src="assets/images/logo-light-text.png" alt="">
                    </td>
                    <td style="width : 50%;">
                        <b style="font-size: 20px">
                            RAFEX DELIVERY
                        </b>
                        <br>
                        <span style="font-size: 13px;">
                            Adresse&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; Hay el Manzeh CYM RABAT<br>
                            Téléphone&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; 06 18 34 54 02<br>
                            E-mail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; Contact@rafex.ma<br>
                            Site web&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; www.rafex.ma<br>
                        </span>
                    </td>
                </tr>

                <tr class="information">
                    <td colspan="3">
                        <table style="font-size: 13px;">
                            <tbody>
                                <tr>
                                    <td style="margin-right: 20px !important;border-style: solid; padding: 20px; width : 50%;">
                                        Livreur&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp; {{$livreur->name}}<br>
                                        Téléphone&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp; {{$livreur->telephone}}<br>
                                        Ville(s)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp; {{$livreur->ville}}<br>
                                    </td>
                                    <td>
                                    </td>
                                    <td
                                        style="border-style: solid;padding: 20px;margin-left: 20px !important; width : 50%;">
                                        Date du distibution&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;{{ date("d/m/Y")}}<br>
                                        Total des commandes&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;{{$total}} commandes<br>
                                        Montant total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;{{$montant}} DH<br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table cellspacing="0px" cellpadding="2px">
                            <tbody>
                                <tr class="heading">
                                    <td style="width:25%;">
                                        Numéro de commande
                                    </td>
                                    <td >
                                        Client
                                    </td>
                                    <td>
                                        Ville
                                    </td>
                                    <td>
                                        Adresse
                                    </td>
                                    <td>
                                        Date de la commande
                                    </td>
                                    <td>
                                        Montant
                                    </td>
                                </tr>
                                @foreach ($commandesPerPage as $commande)
                                <tr class="item">
                                    <td style="width:25%;">
                                        {{$commande->numero}}
                                    </td>
                                    <td >
                                        {{$commande->nom}}
                                    </td>
                                    <td>
                                        {{$commande->ville}}
                                    </td>
                                    <td style="font-size: 8px">
                                        {{$commande->adresse}}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($commande->created_at)->format('j F, Y')}}
                                    </td>
                                    <td>
                                        @if ($commande->montant == 0)
                                        Credit Card
                                        @else
                                        {{$commande->montant}} DH
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endforeach




</body>
</html>
