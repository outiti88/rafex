<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ticket des commandes </title>
        <style type="text/css">
            html { margin: 0px}
            body {
                margin-right: 8px;
                margin-top: 16px;
                margin-left: 4px;
            }
            .page-break {
                        page-break-after: always;
            }
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border-style: solid;
                border-color: black;
            }
            .tg tr{
                height: 40px;
            }
            .tg td {
                border-color: black;
                border-style: solid;
                border-width: 1px;
                font-family: monospace;
                @if ($size == 'A6') font-size : 12px; @else font-size : 6px !important;  @endif
                overflow: hidden;
                @if ($size == 'A6') padding: 10px 5px; @endif
                word-break: normal;
            }

            .tg th {
                border-color: black;
                border-style: solid;
                border-width: 1px;
                font-family: Arial, sans-serif;
                @if ($size == 'A6') font-size : 14px; @else font-size : 3px !important;  @endif
                font-weight: normal;
                overflow: hidden;
                padding: 10px 5px;
                word-break: normal;
            }

            .tg .tg-0lax {
                text-align: left;
                vertical-align: top
            }
            .solid-top {
                border-top: solid;
                border-top-color: black;
            }

            .dotted-right {
                border-right: dashed !important;
                border-color: #474747;
                @if ($size == 'A6') border-width: 4px; @else border-width: 2px; !important;  @endif
            }
            .dotted-top{
                border-top-style: dashed !important;
                border-color: #474747;
                border-width: 2px;
            }

        </style>
    </head>

    <body>
        @foreach ($commandes as $index => $commande)
            @for ($i = 1; $i <= $commande->colis; $i++)
                <div class="container page-break">
                    <table class="tg">
                        <thead>
                            <tr>
                                <th class="tg-0lax" colspan="3" rowspan="2">
                                    @if ($size == 'A6')
                                    <img src="assets/images/logo-light-text.png" style="width: 200px; position:relative; top:10px;" class="light-logo-small">
                                    @else
                                    <img src="assets/images/logo-light-text.png" style="width: 100px; position:relative; top:10px;" class="light-logo-small">
                                    @endif
                                </th>
                                <th class="tg-0lax" style="text-align: center;" rowspan="2">
                                    @if ($size == 'A6')
                                    <img src="uploads/commandesQRCODE/{{$filesName[$index]}}" style="width: 60px;position:relative; top:10px;" class="light-logo-small">
                                    @else
                                    <img src="uploads/commandesQRCODE/{{$filesName[$index]}}" style="width: 30px;position:relative; top:10px;" class="light-logo-small">
                                    @endif
                                </th>
                            </tr>
                            <tr>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="solid-top">
                                <td class="tg-0lax dotted-right" colspan="3"> Référence: <strong>{{$commande->numero}}</strong></td>
                                <td class="tg-0lax" rowspan="2" style="text-align: center;">
                                    Expéditeur: <br><br>
                                    <strong>
                                        @if ($commande->user()->first()->storeName == null)
                                        {{$commande->user()->first()->name}}
                                        @else
                                        {{$commande->user()->first()->storeName}}
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3">Date: <strong>{{$commande->created_at}}</strong></td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3">Client: <strong>{{$commande->nom}}</strong></td>
                                <td class="tg-0lax" rowspan="2" style="text-align: center;">Téléphone: <br><br> <strong>{{$commande->telephone}}</strong></td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3">Adresse: <strong>{{$commande->adresse}}</strong></td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3">
                                    Produit:
                                    @forelse ($commande->produits()->get() as $produit)
                                    <strong>{{$produit->libelle}}</strong> <br>
                                    quantité : <strong>{{$produit->pivot->qte}}</strong>
                                    @empty
                                    <strong>Produit non-spécifiée</strong>
                                    @endforelse
                                </td>
                                <td class="tg-0lax" rowspan="2" style="text-align: center;">Note:</td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3">
                                    Montant:
                                    <strong>
                                        @if ($commande->montant == 0)
                                        Payé par Carte bancaire
                                        @else
                                        {{$commande->montant}} DH
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" colspan="3" rowspan="2">Ouverture: <br>
                                    <strong>
                                        @if ($commande->isOpen)
                                        Le client peut ouvrir le colis
                                        @else
                                        Merci de ne pas ouvrir le colis
                                        @endif
                                    </strong> <br>
                                        @if ($commande->is_fragile)
                                            @if ($size == 'A6')
                                            <img src="assets/images/fragile.png" style="width: 150px" class="light-logo-small">
                                            @else
                                            <img src="assets/images/fragile.png" style="width: 70px" class="light-logo-small">
                                            @endif
                                        @endif
                                </td>
                                <td class="tg-0lax" rowspan="2" style="text-align: center;">
                                    @if ($size == 'A6')
                                    <img src="uploads/commandesQRCODE/{{$filesName[$index]}}" style="width: 60px;position:relative; top:10px;" class="light-logo-small">
                                    @else
                                    <img src="uploads/commandesQRCODE/{{$filesName[$index]}}" style="width: 30px;position:relative; top:10px;" class="light-logo-small">
                                    @endif
                                </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr class="dotted-top">
                                <td class="tg-0lax dotted-right" style="font-size: 8px" colspan="3">
                                    <strong>RAFEX Delivery - Livraison E-commerce</strong><br>
                                    RAFEX Delivery SARL, n'est pas responsable de vos achats.
                                </td>
                                <td class="tg-0lax" style="text-align: center;">www.Rafex.ma</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endfor
        @endforeach
    </body>
</html>
