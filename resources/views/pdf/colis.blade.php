<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <head> <meta charset="UTF-8">
        <title>Ticket des commandes </title>
        <style>
                *{

                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    @if ($size == 'A6') font-size : 10px; @else font-size : 5px;  @endif
                    padding:2px;
                    margin:0;
                }
                .page-break {
                    page-break-after: always;
                }
                h2{
                    text-align : center;
                    font-size: 1.5em;
                    border: 1px solid #467a0f;
                }

            .container{
                box-sizing: border-box;
                width:100%
                height:auto;
                @if ($size == 'A6')  padding-top: 10px !important; @else  padding-top: 5px !important;  @endif
            }
            .tableau{
                @if ($size == 'A6')  padding-top:20px; @else  padding-top: 6px !important;  @endif
                width:100%;
            }

            #customers {
                text-align:center;
                border-collapse: collapse;
                width: 100%;
            }
            h1{
                text-align : center;
                font-size: 2em;
            }
            #customers td, #customers th {
                border: 1px solid #467a0f;
            }
            #customers tr:nth-child(even){
                background-color: #f2f2f2;
            }
            #customers th {
                @if ($size == 'A6')  padding-top:12px; padding-bottom: 10px; @else  padding-top: 6px !important; padding-bottom: 5px; @endif
                color: black;
            }
            </style>
</head>
<body>
     @foreach ($commandes as $index => $commande)
        @for ($i = 1; $i <= $commande->colis; $i++)
            <div class="container page-break" >

                <h1 style="color:#467a0f">
                Ticket de Commande  {{$i}}/ {{$commande->colis}}
                </h1>
                <div class="tableau">

                    <table id="customers">
                    <tr>
                        <th>Commande Numero: </th>
                        <td> {{$commande->numero}}</td>
                    </tr>
                    <tr>
                        <th>Entreprise:  </th>
                        <td>
                            @if ($commande->user()->first()->storeName == null)
                            {{$commande->user()->first()->name}}
                            @else
                            {{$commande->user()->first()->storeName}}
                            @endif
                        </td>
                    </tr>
                    </table>
                </div>
                <h2>Montant Total :
                    @if ($commande->montant == 0)
                    Payé par Carte bancaire"
                    @else
                    {{$commande->montant}} DH
                    @endif
                </h2>
                <div class="tableau">
                    <table id="customers">
                        <tr>
                            <th>
                                Nom & Prénom:
                            </th>
                            <td>
                                {{$commande->nom}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Ville:
                            </th>
                            <td>
                                {{$commande->ville}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Adresse:
                            </th>
                            <td>
                                {{$commande->adresse}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Téléphone:
                            </th>
                            <td>
                                {{$commande->telephone}}
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="tableau">

                    <table id="customers">
                    <tr>
                        <th>Livreur: </th>
                        <td>Rafex Delivery</td>
                    </tr>
                    <tr>
                        <th>Site web:  </th>
                        <td>www.Rafex.ma</td>
                    </tr>
                    </table>
                </div>
                <h2>
                    @if ($commande->isOpen)
                    Le client peut ouvrir le colis
                    @else
                    Merci de ne pas ouvrir le colis
                    @endif
                </h2>
                <div style="padding-top:2px;">
                    <div class="logo-text" style="float: left" >
                        @if ($size == 'A6')
                        <img src="https://rafex.ma/imgs/C-avallo.png" style=" WIDTH: 100PX;"class="light-logo" alt="homepage" />
                        @else
                        <img src="https://rafex.ma/imgs/C-avallo.png" style=" WIDTH: 50PX;"class="light-logo" alt="homepage" />
                        @endif
                    </div>
                </div>
                @if ($size == 'A6')
                <div style="display: block; float : right; ">
                    <div class="logo-text" style="display: inline-block;">
                        <img src="uploads/commandesQRCODE/{{$filesName[$index]}}" style="width: 70px; float : right;" class="light-logo-small">
                    </div>
                </div>
                @endif
            </div>
        @endfor
     @endforeach
</body>
</html>
