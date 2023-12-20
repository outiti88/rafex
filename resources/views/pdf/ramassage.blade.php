<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket de ramassage</title>
    <style>
        * {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            font-size: 14px;
            padding: 2px;
            margin: 0;
        }

        .ticket,
        .top,
        .bottom {
            margin: 20px;
        }

        .top {
            text-align: center;
        }

        .bandname {
            font-weight: bold;
        }

        .deetz,
        .event,
        .price {
            display: block;
            margin: 0;
        }

        .deetz::after {
            content: "";
            display: table;
            clear: both;
        }

        .deetz .event,
        .deetz .price {
            float: left;
            width: 48%;
        }

        .date,
        .location,
        .label {
            font-weight: bold;
        }

        .rip {
            text-align: center;
        }

        .barcode {
            height: 150px;
        }

        .logo-text {
            padding-top: 20px;
        }

        .light-logo {
            width: 130px;
        }

        .light-logo-small {
            width: 10%;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="top">
            <div class="bandname">
                <h2 style="font-size: 20px">Ticket de ramassage : {{$ramassage->reference}}</h2>
            </div>
            <br>
            <div class="deetz">
                <div class="event">
                    <div class="date">Date de la demande </div>
                    <div class="location">{{$ramassage->prevu_at}}</div>
                </div>
                <div class="price">
                    <div class="label">Nombre de colis</div>
                    <div class="cost">{{$ramassage->number}}</div>
                </div>
            </div>
            <br>
            <div class="deetz">
                <div class="event">
                    <div class="date">Client</div>
                    <div class="location">{{$ramassage->user->name}}</div>
                </div>
                <div class="price">
                    <div class="label">Ville</div>
                    <div class="cost">{{$ramassage->city}}</div>
                </div>
            </div>
        </div>
        <div class="rip">------------------------------------------------------------------------------------</div>
        <div class="bottom" style="height: 150px">
            <div class="barcode">
                <div style="display: block; margin: 0; text-align: center; padding-bottom: 20px;">

                    <div style="display: inline-block; margin: 0; text-align: center;">
                        <div class="logo-text" style="display: inline-block;">
                            <img src="uploads/ramassageQRCODE/{{$qrImage}}" style="width: 50%;" class="light-logo-small">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
